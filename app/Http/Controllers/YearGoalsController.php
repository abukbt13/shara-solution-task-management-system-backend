<?php

namespace App\Http\Controllers;

use App\Models\YearGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class YearGoalsController extends Controller
{
    public function store(Request $request){
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
        $currentDate = Carbon::now()->format('j, n, Y');
        $currentTime = Carbon::now()->format('H:i');
        $yeargoal->goal=$data['goal'];
        $yeargoal->date=$currentDate;
        $yeargoal->time=$currentTime;
        $yeargoal->save();

        return response([
            'status' => 'success',
            'message' =>$yeargoal
        ]);
    }
}
