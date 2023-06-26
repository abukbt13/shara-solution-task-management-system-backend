<?php

namespace App\Http\Controllers;

use App\Models\YouTube;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class YouTubeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rules=[
            'name'=>'required',
            'link'=>'required',
        ];
        $data=request()->all();
        $valid=Validator::make($data,$rules);
        if(count($valid->errors())){
            return response([
                'message' =>'failed',
                'data' => $valid->errors()
            ]);
        }
        $youtube=new YouTube();
        $user_id = auth::user();
        $user_id =$user_id->id;
        $currentDate = Carbon::now()->format('j, n, Y');
        $currentTime = Carbon::now()->format('H:i');
        $youtube->date=$currentDate;
        $youtube->time=$currentTime;
        $youtube->link=$data['link'];
        $youtube->user_id=$user_id;
        $youtube->name=$data['name'];
        $youtube->save();

        return response([
            'message'=>'added successfully',
            'data'=>$youtube
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\YouTube  $youTube
     * @return \Illuminate\Http\Response
     */
    public function show(YouTube $youTube)
    {
        $youTube=YouTube::all();
        return response($youTube);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\YouTube  $youTube
     * @return \Illuminate\Http\Response
     */
    public function edit(YouTube $youTube)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\YouTube  $youTube
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YouTube $youTube)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\YouTube  $youTube
     * @return \Illuminate\Http\Response
     */
    public function destroy(YouTube $youTube)
    {
        //
    }
}
