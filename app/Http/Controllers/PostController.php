<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Resources\UserResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = User::where('id', Auth::user()->id)->with('studyFields')->first();
        foreach ($currentUser['studyFields'] as $studyField) 
        {
            $posts = Post::getPosts($studyField['id']);
        }
        return $posts;
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

        $validatePost = Validator::make(
            $request->all(),
            [
                'content' => 'required',
                'from' => 'required',
            ]
        );

        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatePost->errors()
            ], 401);
        }

        $to = null;

        if ($request->to) 
        {
            $to = $request->to;
        }

        Post::create([
            'user_id' => Auth::user()->id,
            'content' => $request->content,
            'from' => $request->from,
            'to' => $to,
            'study_field_id' => $request->selectedStudyField
        ]);

        return response()->json([
            'status' => true,
            'message' => 'ok'
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
