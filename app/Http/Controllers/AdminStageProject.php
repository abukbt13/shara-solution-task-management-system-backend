<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminStageProject extends Controller
{
    public function active_tasks(){
        $admin_id=Auth::user();
        $admin_id=$admin_id->id;
        $task = Task::where('task_type', '=', 'Assigned')
            ->where('admin_id','=', $admin_id)
            ->where('stage', '=', 'active')
            ->get();
        return response()->json($task);
    }

    public function pending_tasks(){
        $admin_id=Auth::user();
        $admin_id=$admin_id->id;
        $task = Task::where('task_type', '=', 'Assigned')
            ->where('admin_id','=', $admin_id)
            ->where('stage', '=', 'pending')
            ->get();
        return response()->json($task);
    }
    public function mark_task_complete($id){
        $admin_id=Auth::user();
        $task=Task::find($id);
        $task->stage='trashed';
        $task->update();

        return response()->json($task);
    }
     public function mark_task_revision($id){
        $admin_id=Auth::user();
        $task=Task::find($id);
        $task->stage='revision';
        $task->update();

        return response()->json($task);
    }
    public function revision_tasks(){
        $admin_id=Auth::user();
        $admin_id=$admin_id->id;
        $task = Task::where('task_type', '=', 'Assigned')
            ->where('admin_id','=', $admin_id)
            ->where('stage', '=', 'revision')
            ->get();
        return response()->json($task);
    }
    public function completed_tasks(){
        $admin_id=Auth::user();
        $admin_id=$admin_id->id;
        $task = Task::where('task_type', '=', 'Assigned')
            ->where('admin_id','=', $admin_id)
            ->where('stage', '=', 'trashed')
            ->get();
        return response()->json($task);
    }
}
