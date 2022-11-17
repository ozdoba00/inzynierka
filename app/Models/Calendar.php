<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Calendar extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
    'name',
    'starts',
    'ends',
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

protected $casts = [
    'starts' => 'datetime:Y-m-d',
];


    public static function getPrivateEvents(int $userId)
    {
        $events = DB::table('calendars_users')
            ->join('calendars', 'calendars_users.calendar_id', '=', 'calendars.id')
            ->select('calendars.*')
            ->where('user_id', $userId)
            ->get();

        return $events;
    }

    public static function getLectureEvents(int $lectureId)
    {
        $events = DB::table('calendars_lectures')
            ->join('calendars', 'calendars_lectures.calendar_id', '=', 'calendars.id')
            ->select('calendars.*')
            ->where('lecture_id', $lectureId)
            ->get();

        return $events;
    }

    public static function getLaboratoryEvents(int $laboratoryId)
    {
        $events = DB::table('calendars_laboratories')
            ->join('calendars', 'calendars_laboratories.calendar_id', '=', 'calendars.id')
            ->select('calendars.*')
            ->where('laboratory_id', $laboratoryId)
            ->get();

        return $events;
    }
    public static function getProjectEvents(int $projectId)
    {
        $events = DB::table('calendars_projects')
            ->join('calendars', 'calendars_projects.calendar_id', '=', 'calendars.id')
            ->select('calendars.*')
            ->where('project_id', $projectId)
            ->get();

        return $events;
    }

    public static function savePrivateEvents(Calendar $event)
    {   
        DB::table('calendars_users')
        ->insert([
            'calendar_id' => $event->id,
            'user_id' => auth()->id()
        ]);
    }
    public static function saveLectureEvents(Calendar $event, int $lectureId)
    {   
        DB::table('calendars_lectures')
        ->insert([
            'calendar_id' => $event->id,
            'lecture_id' => $lectureId
        ]);
    }
    public static function saveLaboratoryEvents(Calendar $event, int $laboratoryId)
    {   
        DB::table('calendars_laboratories')
        ->insert([
            'calendar_id' => $event->id,
            'laboratory_id' => $laboratoryId
        ]);
    }
    public static function saveProjectEvents(Calendar $event, int $projectId)
    {   
        DB::table('calendars_projects')
        ->insert([
            'calendar_id' => $event->id,
            'project_id' => $projectId
        ]);
    }
}
