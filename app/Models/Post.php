<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


 protected $fillable = [
        'content', 'study_group_id', 'user_id', 'type', 'calendar', 'from', 'to'
    ];

    public function user()
    {
        return $this-> belongsTo('App\Models\User');
    }
}
