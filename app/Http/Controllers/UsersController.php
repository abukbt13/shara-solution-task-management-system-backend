<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;


class UsersController extends Controller
{
    public function store(Request $request){
        $rules=[
            'name' =>'required|max:255',
            'email' =>'required|email|unique:users',
            'password' =>'required',
            'c_password'=>'required|same:password'
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

            $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'success' =>true,
            'user' => $user
                        ],200);
            activity()
            ->causedBy($user) // The user who performed the action
            ->log('User created', ['user_id' => $user->id]);

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
        $user = User::where('email', $email)->get()->first();

        if(Auth::attempt(['email'=>$email,'password'=>$password])){
            $token = $user->createToken('token')->plainTextToken;

            return response([
                'status'=>'success',
                'token'=>$token,
//                'access_token'=>$token->plainTextToken,
                'user'=>request()->user()
            ]);
        }
      else{
          return response([
              'status'=>'fail',
              'message'=>'faile login'
          ]);
      }
    }
    public function auth()
    {
        $user = Auth::user();
        echo $user;
        if ($user) {

            return response()->json([
                'success' => true
            ]);
        }
        else{
            return response()->json([
                'fail' => true
            ],403);
        }

    }
    public function logout()
    {
        $user = Auth::user();
        $tokens = $user->tokens;
    
        // Alternatively, delete all the user's tokens
        $tokens->each(function ($token) {
            $token->delete();
        });
        return response()->json([
            'success' => 'Logout successfully'
        ]);

    }

    

}
