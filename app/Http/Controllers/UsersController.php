<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function store(Request $request){
        $rules=[
            'name' =>'required|max:255',
            'email' =>'required|email|unique:users',
            'password' =>'required|confirmed'
        ];
        $data=request()->all();
        $valid=Validator::make($data,$rules);
        if(count($valid->errors())){
            return response([
                'status'=>'failed',
                'error'=>$valid->errors()
            ],422);
        } 
            
            $user=new User();
            $user->name=$data['name'];
            $user->email=$data['email'];
            $user->password=Hash::make($request->password);
            $user->save();

            return response()->json([
                          'success' =>true,
                            'user' => $user
                        ],200);
        
    }

    public function login(Request $request){
        $data = request()->all();
        $rules = [
            'email'=>'required',
            'password'=>'required'
        ];
        $valid = Validator::make($data,$rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ], 422);
        }
        $email = request('email');
        $password = request('password');
        if(Auth::attempt(['email'=>$email,'password'=>$password])){
            $token = request()->user()->createToken('api_token_at_'.now()->toDateTimeString());
            return response([
                'status'=>'success',
                'access_token'=>$token->plainTextToken,
                'user'=>request()->user()
            ]);
        }
        return response([
            'status'=>'failed',
            'errors'=>['email'=>['Invalid email or password']]
        ],422);
    }
}
