<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\WeekGoal;
use App\Models\YearGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WeekGoalsController extends Controller
{
    public function storeCompany(Request $request)
    {

        $company = new Company();
        $company->company_name = 'vb';
        $company->company = 'vgbhnjk';
        $path = $request->file('company_logo')->store('public/company');
        $filename = basename($path);
        $company->company_logo = $filename;
        $company->save();
        return response()->json([
            'message' => 'You have successfully uploaded the company details'
        ]);

    }
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
    public function all_weeks_goals(){
        $weekGoals=WeekGoal::all();
        return response()->json($weekGoals);
    }
    public function all_yearly_goals(){
        $yearGoals=YearGoal::all();
        return response()->json($yearGoals);
    }
    public function getRandomWeekGoal(){
        $weekGoals=DB::table('week_goals')->inRandomOrder()->limit(1)->get();
        return response($weekGoals);
    }
    public function getRandomYearGoal(){
        $yearGoals=DB::table('year_goals')->inRandomOrder()->limit(1)->get();
        return response($yearGoals);
    }
}
