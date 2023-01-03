<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [
        'content', 'study_field_id', 'user_id', 'from', 'to', 
    ];
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:00',
    ];

    public function user()
    {
        return $this-> belongsTo('App\Models\User');
    }

    public static function getPosts($studyFieldId)
    {
        return DB::table('posts')
        ->join('users', 'posts.user_id', '=', 'users.id')
        ->select('posts.*', 'users.name', 'users.last_name', 'users.profile_image')
        ->where('posts.study_field_id', $studyFieldId)
        ->orderBy('created_at', 'DESC')
        ->get();
    }
}
