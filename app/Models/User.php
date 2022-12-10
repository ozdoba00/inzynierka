<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'email_verified_at',
        'gender',
        'date_of_birth',
        'profile_image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = ['studyFields'];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'datetime'
    ];
    

    public function offer()
    {
        return $this->hasOne('App\Models\Offer');
    }

    public function post()
    {
        return $this->hasOne('App\Models\Post');
    }

    public function studyFields()
    {
        return $this->belongsToMany('App\Models\FieldOfStudy', 'users_fields_of_study', 'user_id', 'study_field_id');
    }

    
   
    public static function getTokenById(int $tokenId)
    {
        $token = DB::table('personal_access_tokens')
            ->select('*')
            ->where('id', $tokenId)
            ->first();
        return $token;
    }

    public static function removeToken(int $tokenId)
    {
        DB::table('personal_access_tokens')
        ->where('id', $tokenId)
        ->delete();
    }
}
