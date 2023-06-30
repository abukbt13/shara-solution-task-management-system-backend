<?php

namespace App\Http\Controllers;

use App\Models\GoogleDocument;
use App\Models\LocalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_local(Request $request)
    {
        $rules=[
            'name'=>'required',
            'filename'=>'required',
        ];
        $data=request()->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors(),
            ]);
        }
        else{


            $path = $request->file('filename')->store('public/Documents');
            $filename = basename($path);

            $user_id=auth::user();
            $user_id=$user_id->id;
            $document = new LocalDocument();
            $document->doc_name=$request->name;
            $document->user_id=$user_id;
            $document->description=$request->description;
            $document->filename=$filename;
            $document->save();
        }



        return response([
            'status' => 'success',
            'message' =>'successfull save the data',
        ]);
        }
    public function create_google(Request $request)
    {
        $rules=[
            'google_name'=>'required',
            'google_link'=>'required',
        ];
        $data=request()->all();
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => $validator->errors(),
            ]);
        }



            $user_id=auth::user();
            $user_id=$user_id->id;
            $document = new GoogleDocument();
            $document->doc_name=$request->google_name;
            $document->user_id=$user_id;
            $document->description=$request->description;
            $document->doc_link=$request->google_link;
            $document->save();

        return response([
            'status' => 'success',
            'message' =>'successfull save the data',
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_google_documents(Request $request)
    {
        $user_id=Auth::user();
        $user_id=$user_id->id;
        $google=GoogleDocument::where('user_id','=',$user_id)->get();
        return response($google);
    }
    public function show_local_documents(Request $request)
    {
        $user_id=Auth::user();
        $user_id=$user_id->id;
        $google=LocalDocument::where('user_id','=',$user_id)->get();
        return response($google);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
