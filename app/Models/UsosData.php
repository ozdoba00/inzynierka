<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class UsosData extends Model
{
    use HasFactory;

    protected $fillable = [
        'oauth_token',
        'oauth_token_secret',
        'oauth_verifier'
    ];

    private $baseUrl = 'https://usosapps.prz.edu.pl/services/';

    public function checkUserApiByEmail(\OAuth $usosApi, string $email): ? array
    {
        $url = $this->baseUrl . 'users/user?fields=email|first_name|last_name|birth_date|student_programmes|sex';
        $usosApi->fetch($url);
        $response_info = json_decode($usosApi->getLastResponse());

        if($email !== $response_info->email)
        {
            return null;
        }
        
        return (array)$response_info;
        
    }

    public function getUserGroups(\OAuth $usosApi): ? array
    {
        $url = $this->baseUrl . 'groups/user?fields=class_type|course_unit_id';
        $usosApi->fetch($url);
        $response_info = json_decode($usosApi->getLastResponse());
        
        return (array)$response_info;
    }

    public static function removeToken($userId)
    {
        $token = DB::table('usos_data_users')
        ->select('usos_data_id')
        ->where('user_id', $userId)
        ->first();

        DB::table('usos_data')
        ->where('id', $token->usos_data_id)
        ->delete();
    }
}
