<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{

    public function index()
    {
        $messages = Message::getMessages(Auth::user()->id);

        foreach ($messages as $message) 
        {
            $message->author = $message->sender_id == Auth::user()->id ? 1 : 0;
        }

        return $messages;
    }

    public function store(Request $request)
    {
        $validatePost = Validator::make(
            $request->all(),
            [
                'content' => 'required',
                'recipient_id' => 'required',
            ]
        );

        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validatePost->errors()
            ], 401);
        }

        $result = Message::create([
            'recipient_id' => $request->recipient_id,
            'sender_id' => Auth::user()->id,
            'content' => $request->content
        ]);

        return $result;
    }


}
