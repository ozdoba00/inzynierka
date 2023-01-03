<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',  'sender_id', 'recipient_id'
    ];

    public static function getMessages($userId)
    {
        return DB::table('messages')
        ->join('users', 'messages.sender_id', '=', 'users.id')
        ->select('messages.*', 'users.name', 'users.last_name', 'users.profile_image')
        ->where('messages.recipient_id', $userId)
        ->get();
    }
}
