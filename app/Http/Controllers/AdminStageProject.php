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
}
