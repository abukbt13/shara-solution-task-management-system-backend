<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ];
        $data = request()->all();
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())){
            return response([
                'status' => 'failed',
                'error' => $valid->errors()
            ]);
        }
        $role_id = Role::where('name','like','user')->first()->id;
        $user = new User();
        $user->name = $data['name'];
        $user->role= 'user';
        $user->email = $data['email'];
        $user->role_id=$role_id;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'status' => 'success',
            'user' => $user
        ]);
    }


    public function login(Request $request)
    {
        $data = request()->all();
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'message' => 'Enter correct details',
                'errors' => $valid->errors()
            ]);
        }
        else{
            $email = request('email');
            $password = request('password');
            $user = User::where('email', $email)->get()->first();

            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $token = $user->createToken('token')->plainTextToken;

                return response([
                    'status' => 'success',
                    'token' => $token,
                    'user' => request()->user()
                ]);
            }
            else{
                return response([
                    'status' => 'failed',
                    'message' => 'Enter correct details',
                ]);
            }
        }

    }

   public function auth(){
       if (Auth::check()) {
           return response()->json(['authenticated' => true]);
       } else {
           return response()->json(['authenticated' => false]);
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
