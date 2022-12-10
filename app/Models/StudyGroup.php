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


    public function users()
    {
        return $this-> belongsToMany('App\Models\User');
    }

    
    public static function saveUserToGroup($userId, $groupId)
    {
        DB::table('study_group_users')
        ->insert([
            'user_id' => $userId,
            'study_group_id' => $groupId
        ]);
    }


    public static function getUserGroups($userId)
    {
        $currentTerm = date('Y');
        return DB::table('study_groups')
        ->join('study_group_users', 'study_group_users.study_group_id', '=', 'study_groups.id')
        ->join('users', 'study_group_users.user_id', '=', 'users.id')
        ->select(['study_groups.name', 'study_groups.id', 'study_groups.type'])
        ->where([['study_group_users.user_id', $userId] , ['study_groups.term', 'LIKE', '%'.$currentTerm. '%']])
        ->get();
    }
}
