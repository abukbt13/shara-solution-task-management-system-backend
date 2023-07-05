<?php

namespace App\Http\Controllers;

use App\Models\ProjectUser;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectUsersController extends Controller
{
    public function addUserToProject()
    {
        $rules=[
            'project_id'=>'required',
            'user_id'=>'required'
        ];
        $data=request()->all();
        $valid=Validator::make($data,$rules);
        if(count($valid->errors())){
            return response([
                'status'=>'failed',
                'errors'=>$valid->errors
            ]);
        }
            $projectId = $data['project_id'];
            $userId = $data['user_id'];


            $project = Project::find($projectId);
            $user = User::find($userId);

            if (!$project || !$user) {
                return response([
                    'status' => 'failed',
                    'message' => 'Project or User not found.',
                ]);
            }

            $project->users()->attach($user);

            return response()->json([
                'status' => 'success',
                'message' => 'User added to the project successfully.',
            ]);
    }
    public function unassignedUsers()
    {
        $users = User::where('role', 'user')
            ->whereDoesntHave('projects')
            ->where('role', false)
            ->get();

        return response()->json([
            'users' => $users,
        ]);
    }
    public function fetchUserProjects()
{
    $user = Auth::user();
    $user_id=$user->id;
    $projects = User::join('project_user', 'users.id', '=', 'project_user.user_id')
        ->join('projects', 'project_user.project_id', '=', 'projects.id')
        ->where('users.id', $user->id)
        ->select('project_user.*', 'projects.*')
        ->get();

    return response($projects);
}

public function mark_pending($id)
{
    $record = ProjectUser::findOrFail($id);
        if ($record->stage === 'active') {
        $record->stage = 'pending';
        $record->save();

        return response(['message' => 'Stage updated successfully']);
    }

    return response(['message' => 'Cannot update stage. Current stage is not active.']);
}
public function mark_complete($id)
{
    $record = Task::findOrFail($id);
    dd($record);
        if ($record->stage === 'pending') {
        $record->stage = 'complete';
        $record->save();

        return response(['message' => 'Stage updated successfully']);
    }

    return response(['message' => 'Cannot update stage. Current stage is not active.']);
}
}
