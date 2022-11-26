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


    public static function addUserToStudyField($userId, $studyFieldId)
    {
        DB::table('users_fields_of_study')
        ->insert([
            'user_id' => $userId,
            'study_field_id' => $studyFieldId
        ]);
    }

    public function users()
    {
        return $this-> belongsToMany('App\Models\User');
    }


}
