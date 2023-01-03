<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
class MessageController extends Controller
{

    public function index()
    {
        return Message::getMessages(Auth::user()->id);
    }
}
