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

    public function show_users()
    {
        $role= Auth::user();
        $role= $role->role;
//        dd(role);
        if($role == 'super_admin'){
            $users=User::all();
//            ('role','!=','super_admin')->get();
            return response()->json($users);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Not authorised'
            ]);
        }

    }
    public function update_user(Request $request,$id)
    {

        $data = request()->all();
        $rules = [
            'role' => 'required',
        ];
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ]);
        }
        $role= Auth::user();
        $role= $role->role;
//        dd(role);
        if($role == 'super_admin'){
            $user=User::find($id);
            $user->role=$request->role;
            $user->update();
            return response()->json($user);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Not authorised'
            ]);
        }

    }
    public function registerAdmin(Request $request, $id=null){
        $rules=[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,user',
        ];
        $data=request()->all();
        $valid=Validator::make($data,$rules);

        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ]);
        }
        $role_id=Role::where('name','like',$data['role'])->first()->id;
        $password=request('email');

        $user= $id===null ?
        User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'role'=>request('role'),
            'password'=>Hash::make($password),
            'role_id'=>$role_id
        ])
        :tap(User::find($id))->update([
            'name'=>request('name'),
            'email'=>request('email'), 
        ]);
        return response([
            'status'=>'success',
            'user'=>$user
        ]);
    }
}
