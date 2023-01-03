<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
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
        'date_of_birth' => 'datetime:m-d-Y'
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

    public static function getFriends(int $fieldId, $userId)
    {
        $events = DB::table('users_fields_of_study')
            ->join('users', 'users_fields_of_study.user_id', '=', 'users.id')
            ->select(['users.name', 'users.last_name', 'users.id', 'users.profile_image'])
            ->where([['users_fields_of_study.study_field_id', '=',$fieldId], ['users_fields_of_study.user_id', '!=',$userId]])
            ->get();
        return $events;
    }
}
