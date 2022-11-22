<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsosData extends Model
{
    use HasFactory;

    protected $fillable = [
        'oauth_token',
        'oauth_token_secret',
        'oauth_verifier'
    ];

    
}
