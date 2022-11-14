<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;


    protected $fillable = [
        'content', 'price', 'user_id'
    ];

    public function user()
    {
        return $this-> belongsTo('App\Models\User');
    }

    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:00',
    ];
}
