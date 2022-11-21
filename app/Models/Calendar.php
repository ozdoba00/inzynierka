<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Calendar extends Model
{
    use HasFactory;
    public $table = 'calendar';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
    'name',
    'ends',
    'starts',
    'status',
    'summary',
    'location',
    'uid',
    'frequency'
];

/**
 * The rules for data entry
 *
 * @var array
 */
public static $rules = [
    'starts' => 'required',
    'ends' => 'required',
    'status' => 'required',
    'summary' => 'required',
];




    public static function getPrivateEvents(int $userId)
    {
        $events = DB::table('calendar_users')
            ->join('calendar', 'calendar_users.calendar_id', '=', 'calendar.id')
            ->select('calendar.*')
            ->where('user_id', $userId)
            ->get();

        return $events;
    }

    public static function getLectureEvents(int $lectureId)
    {
        $events = DB::table('calendar_lectures')
            ->join('calendar', 'calendar_lectures.calendar_id', '=', 'calendar.id')
            ->select('calendar.*')
            ->where('lecture_id', $lectureId)
            ->get();

        return $events;
    }

    public static function getLaboratoryEvents(int $laboratoryId)
    {
        $events = DB::table('calendar_laboratories')
            ->join('calendar', 'calendar_laboratories.calendar_id', '=', 'calendar.id')
            ->select('calendar.*')
            ->where('laboratory_id', $laboratoryId)
            ->get();

        return $events;
    }
    public static function getProjectEvents(int $projectId)
    {
        $events = DB::table('calendar_projects')
            ->join('calendar', 'calendar_projects.calendar_id', '=', 'calendar.id')
            ->select('calendar.*')
            ->where('project_id', $projectId)
            ->get();

        return $events;
    }

    public static function savePrivateEvents(Calendar $event)
    {   
        DB::table('calendar_users')
        ->insert([
            'calendar_id' => $event->id,
            'user_id' => auth()->id()
        ]);
    }
    public static function saveLectureEvents(Calendar $event, int $lectureId)
    {   
        DB::table('calendar_lectures')
        ->insert([
            'calendar_id' => $event->id,
            'lecture_id' => $lectureId
        ]);
    }
    public static function saveLaboratoryEvents(Calendar $event, int $laboratoryId)
    {   
        DB::table('calendar_laboratories')
        ->insert([
            'calendar_id' => $event->id,
            'laboratory_id' => $laboratoryId
        ]);
    }
    public static function saveProjectEvents(Calendar $event, int $projectId)
    {   
        DB::table('calendar_projects')
        ->insert([
            'calendar_id' => $event->id,
            'project_id' => $projectId
        ]);
    }
}
