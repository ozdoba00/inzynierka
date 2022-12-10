<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validatePost = Validator::make($request->all(),
        [
            'content' => 'required',
            'from' => 'required',
            'to' => 'required',
            'calendar' => 'required',
            'type' => 'required'
        ]);  


    if($validatePost->fails()){
        return response()->json([
            'status' => false,
            'message' => 'validation error',
            'errors' => $validatePost->errors()
        ], 401);
    }

    $studyGroupId = null;
    if($request->type == '2')
    {
        $studyGroupId = $request->studyGroup;
    }

    Post::create([
        'user_id' => Auth::user()->id,
        'study_group_id' => $studyGroupId,
        'content' => $request->content,
        'type' => $request->type,
        'calendar' => $request->calendar,
        'from' => $request->from,
        'to' => $request->to
    ]);

    return response()->json([
    'status'=>true,
    'message'=>'ok'
]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
