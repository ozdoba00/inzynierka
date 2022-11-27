<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class StudyGroup extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'usos_id', 'name', 'type', 'term'
    ];

    
    public static function saveUserToGroup($userId, $groupId)
    {
        DB::table('study_group_users')
        ->insert([
            'user_id' => $userId,
            'study_group_id' => $groupId
        ]);
    }
}
