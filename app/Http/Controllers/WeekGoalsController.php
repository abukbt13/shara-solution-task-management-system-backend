<?php

namespace App\Http\Controllers;

use App\Models\WeekGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class WeekGoalsController extends Controller
{
    public function store(Request $request){
        $rules=[
            'goal'=>'required|unique:week_goals',
        ];
        $data=request()->all();
        $valid=Validator::make($data,$rules);
        if(count($valid->errors())){
            return response()->json([
               'message' =>'failed',
                'data' => $valid->errors()
            ]);
        }
        $weekgoal=new WeekGoal();
        $currentDate = Carbon::now()->format('j, n, Y');
        $currentTime = Carbon::now()->format('H:i');
        $weekgoal->date=$currentDate;
        $weekgoal->time=$currentTime;
        $weekgoal->goal=$data['goal'];
        $weekgoal->save();

        return response()->json([
            'message'=>'added successfully',
            'data'=>$weekgoal
        ]);
    }
}
