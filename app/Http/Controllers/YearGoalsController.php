<?php

namespace App\Http\Controllers;

use App\Models\YearGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class YearGoalsController extends Controller
{
    public function store_yeargoal(Request $request){
        $rules=[
            'goal'=>'required|unique:year_goals',
        ];
        $data=request()->all();
        $valid=Validator::make($data,$rules);
        if(count($valid->errors())){
            return response([
                'status'=>'failed',
                'message'=>$valid->errors
            ]);
        }
        $yeargoal=new YearGoal();
        $user_id=Auth::user();
        $user_id=$user_id->id;
        $currentDate = Carbon::now()->format('j, n, Y');
        $currentTime = Carbon::now()->format('H:i');
        $yeargoal->goal=$data['goal'];
        $yeargoal->date=$currentDate;
        $yeargoal->user_id=$user_id;
        $yeargoal->time=$currentTime;
        $yeargoal->save();

        return response([
            'status' => 'success',
            'message' =>$yeargoal
        ]);
    }
}
