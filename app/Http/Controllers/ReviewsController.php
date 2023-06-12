<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    public function store(Request $request){
        $rules=[
            'description|unique',
        ];
        $data=request()->all();
        $valid=Validator::make($data,$rules);
        if(count($valid->errors())){
            return response([
                'status'=>'failed',
                'message'=>$valid->errors
            ]);
        }
        $user_id=Auth::user()->id;
        $review=new Review();
        $currentDate=Carbon::now()->format('j-n-Y');
        $currentTime=Carbon::now()->format('H:i');
        $review->description=$data['description'];
        $review->date=$currentDate;
        $review->time=$currentTime;
        $review->user_id=$user_id;
        $review->save();

        return response([
            'message'=>'Success',
            'data'=>$review
        ]);
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'description' => 'required|max:255',
            'user_id' => 'required|integer',
        ]);

        $review = Review::find($id);

        $review->todo = $data['todo'];
        $review->user_id = $data['user_id'];

        $review->save();

    return response()->json([
          'message' =>"updated successfully",
            'data' => $review
        ]);
    }
}
