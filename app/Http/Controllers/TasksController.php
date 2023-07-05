<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isNull;

class TasksController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'task_type' =>'required',
            'todo' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([

                'status' => 'failed',
                'message' => 'Validation failed',
                'data' => $validator->errors(),
            ], 422);

        }
        $todoId=$request->todo_id;
//        echo $todoId;
//        die();

        if (is_null($todoId)) {
            $task=new Task();
            $task->todo = $request->input('todo');
            $task->task_type = $request->input('task_type');
            $task->date = now()->format('j, n, Y');
            $task->time = now()->format('H:i');
            $task->user_id = auth()->user()->id;
            $task->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Task added successfully',
                'data' => $task,
            ]) ;
        }else{
            $task=Task::find($todoId);
                $task->todo = $request->input('todo');
                $task->update();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Task Updated  successfully',
                    'data' => $task,
                ]);
            }
        }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'todo' => 'required|max:255',
            'user_id' => 'required|integer',
        ]);

        $task = Task::find($id);

        $task->todo = $data['todo'];
        $task->user_id = $data['user_id'];

        $task->save();

    return response()->json([
          'message' =>"updated successfully",
            'data' => $task
        ]);
    }
    public function destroy(Request $request, $id){
        $task = Task::find($id);
        $task->stage='trashed';
        $task->status='inactive';
        $task->update();
        return response()->json([
           'message' =>"deleted successfully",
            'data' => $task
        ]);
    }
    public function trashed_tasks(){
        $user_id=auth::user();
        $user_id=$user_id->id;
        $task = Task::where('status','=','inactive')->where('stage','=','trashed')->where('user_id','=',$user_id)->get();
        return response()->json($task);
    }
    public function show($id){

        $tasks=Task::where('project_id','=',$id)->get();

        return response()->json($tasks);
    }

    public function users_to_be_added_to_task(Request $request,$id){

        $users=User::where('role','=','user')->get();

        return response()->json($users);
    }

    public function show_user_tasks(){
        $user_id = Auth::user();
        $user_id = $user_id->id;
        $tasks=Task::where('user_id','=',$user_id)->where('status','=','active')->get();

        return response()->json($tasks);
    }
    public function show_user_completed_tasks(){
        $user_id = Auth::user();
        $user_id = $user_id->id;
        $tasks=Task::where('user_id','=',$user_id)->where('status','=','inactive')->where('stage','=','active')->get();

        return response()->json($tasks);
    }

    public function create_task(Request $request,$id){
        $rules = [
            'task' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([

                'status' => 'failed',
                'message' => 'Validation failed',
                'data' => $validator->errors(),
            ], 422);

        }
        $task = new Task();

        $task->project_id=$id;
        $task->todo=$request->task;
        $task->user_id=$request->user_id;
        $task->task_type=$request->task_type;
        $task->date=$request->date_line;
        $task->time=$request->time_line;
        $task->description=$request->description;
        $task->save();
        return response()->json($task);
    }


    public function edit(Request $request,$id){
        $task=Task::where('id',$id)->first();
        return response()->json($task);
    }
    public function updateone(Request $request, $id) {
        $task = Task::where('id',$id)->get()->first(); // Find the task with the given ID
        $task->todo =$request->todo;
        $task->save();
        return response()->json([
            'message' =>"updated successfully",
            'data' => $task
        ]);
    }

    public function get_reviews(Request $request){

        $reviews=Review::all();
        return response($reviews);
    }
    public function mark_completed(Request $request, $id){

        $task=Task::find($id);

        $task->status='inactive';
        $task->update();
        return response()->json(
            [
                'status' =>'Task completed successfully',
                'data' =>$task
            ]
        );
    }

}
