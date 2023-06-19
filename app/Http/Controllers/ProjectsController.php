<?php

namespace App\Http\Controllers;

use App\Models\ProjectUser;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{
    public function store($id = null)
    {
        $rules = [
            'name'=>'required|unique:projects',
            'description'=>'required',
        ];
        $data = request()->all();
        $valid = Validator::make($data,$rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ]);
        }
        $user_id = Auth::user();
        $user_id=$user_id->id;

//        $project = $id == null ?
//            Project::create([
//                'name'=>request('name'),
//                'description'=>request('description'),
//            ])
//            : tap(Project::find($id))->update([
//                'name'=>request('name'),
//                'description'=>request('description'),
//            ]);
        $project = new Project();

        $project->name = $data['name'];
        $project->description = $data['description'];
        $project->user_id =$user_id;
        $project->save();
            return response([
                'status'=>'success',
                'data'=>$project
            ]);
    }
    public function edit(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->name = $request->name;
        $project->description = $request->description;
        $project->update();

        return response([
            'status' => 'success',
            'message' => 'The project is successfully updated',
            'data' => $project
        ]);
    }
    public function listProjects(){
        $projects=Project::all();
        return response([
            'status'=>'success',
            'data'=>$projects
        ]);
    }
    public function fetch_overview($id){
        $projects = Project::findOrFail($id);
        return response($projects);
    }

    public function list_users_not_in_project($id)
    {
        $users = DB::table('users')
            ->leftJoin('project_user', function ($join) use ($id) {
                $join->on('project_user.user_id', '=', 'users.id')
                    ->where('project_user.project_id', $id);
            })
            ->select('users.id', 'users.email', 'users.name', 'project_user.user_id', 'project_user.project_id')
            ->whereNull('project_user.user_id')
            ->get();

        return response($users);
    }

    public function add_user_to_the_current_project(Request  $request){
        $project_user = new ProjectUser();
        $project_user->user_id = $request->user_id;
        $project_user->project_id = $request->project_id;
        $project_user->save();
        return response(
            [
                'data' =>$project_user,
                'status' =>'success',
                'message' =>'You have successfully added a user to the project'
            ]
        );
    }
    public function fetch_users_to_the_current_project($id){
        $project_id = $id;

        $users = DB::table('users')
            ->join('project_user', 'project_user.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', 'project_user.project_id')
            ->where('project_user.project_id', '=', $project_id)
            ->get();

        return response($users);
    }
}
