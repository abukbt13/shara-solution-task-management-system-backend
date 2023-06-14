<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
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
        $project = $id == null ?
            Project::create([
                'name'=>request('name'),
                'description'=>request('description'),
            ])
            : tap(Project::find($id))->update([
                'name'=>request('name'),
                'description'=>request('description'),
            ]);
            return response([
                'status'=>'success',
                'data'=>$project
            ]);
    }
}
