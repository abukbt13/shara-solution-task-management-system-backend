<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProjects extends Controller
{
    public function active_tasks($id){
        $user_id=auth::user();
        $user_id=$user_id->id;
        $task=Task::where('task_type','=','assigned')->where('project_id','=',$id)->where('stage','=','active')->where('user_id','=',$user_id)->get();
        return response($task);
    }
    public function pendind_tasks(){
        $task=Task::all();
        return response($task);
    }
    public function completed_tasks(){
        $task=Task::all();
        return response($task);
    }
    public function revision_tasks(){
        $task=Task::all();
        return response($task);
    }

    public function mark_complete($id)
    {
        $user_id = Auth::user()->id;
        $record = Task::findOrFail($id);
        
        if ($record && $user_id) {
            if (in_array($record->stage, ['active', 'pending', 'revision'])) {
                $record->stage = 'pending';
                $record->save();
                
                return response(['message' => 'Stage updated successfully']);
            }
        }
          
        return response(['message' => 'Cannot update stage. Current stage is not active, pending, or revision.']);
    }
}
