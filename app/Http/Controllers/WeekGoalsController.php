<?php

namespace App\Http\Controllers;

use App\Models\WeekGoal;
use App\Models\YearGoal;
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
    public function show(){
        $weekGoals=WeekGoal::all();
        return response()->json($weekGoals);
    }
    public function getRandomWeekGoal(){
        $weekGoals=WeekGoal::all();
        $weekGoal=rand(0,count($weekGoals)-1);
        return response()->json($weekGoals[$weekGoal]);
    }
    public function getRandomYearGoal(){
        $yearGoals=YearGoal::all();
        $yearGoal=rand(0,count($yearGoals)-1);
        return response()->json($yearGoals[$yearGoal]);
    }
}
