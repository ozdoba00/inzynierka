<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FieldOfStudy extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = 'fields_of_study';
    protected $fillable = [
        'name', 'usos_id'
    ];


    public static function getStudyFieldsByUser($userId)
    {
        return DB::table('fields_of_study')
        ->join('users_fields_of_study', 'fields_of_study.id', '=', 'users_fields_of_study.study_field_id')
        ->select('fields_of_study.*')
        ->where('users_fields_of_study.user_id', '=', $userId)
        ->get();
    }

    public static function addUserToStudyField($userId, $studyFieldId)
    {
        DB::table('users_fields_of_study')
        ->insert([
            'user_id' => $userId,
            'study_field_id' => $studyFieldId
        ]);
    }

   


}
