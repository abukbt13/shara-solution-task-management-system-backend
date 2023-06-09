<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function store($id = null)
    {
        $rules = [
            'name'=>'required|unique:roles',
            'description'=>'required',
            
        ];
        $role = request()->all();
        $valid = Validator::make($role,$rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ]);
        }
        $role = $id == null ?
            Role::create([
                'name'=>request('name'),
                'description'=>request('description'),
            ])
            : tap(Role::find($id))->update([
                'name'=>request('name'),
                'description'=>request('description'),
            ]);
            return response([
                'status'=>'success',
                'data'=>$role
            ]);
    }

    public function listOfRoles(){
        $roles=Role::all();
        return response([
            'status'=>'success',
            'data'=>$roles
        ]);
    }
    
    public function list() {
        $roles = Role::select('name', 'id')->get();

        foreach ($roles as $role) {
            return response ([
                $role->id,
                $role->name
            ]);
        }
    }
}
