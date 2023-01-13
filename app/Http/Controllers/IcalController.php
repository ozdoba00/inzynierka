<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Support\Facades\File;
use App\Providers\iCalEasyReader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class ICalController extends Controller
{


    public function index(Request $request)
    {
        $filters = $request->filters;
        $userId = auth()->id();
        $events = [];
        
        if (!empty($filters)) {

            if (!empty($filters['private'])) {

                $events[] = Calendar::getPrivateEvents($userId);
            }
            
        }

        return ['events' => $events];
    }

    public function store(Request $request)
    {

        $data = $request->all();


        try {
            $onSave = Calendar::create($data);
            Calendar::savePrivateEvents($onSave);
        } catch (\Throwable $th) {
            return ['error' => $th];
        }

        return ['status' => '200'];
    }

    public function update($id, Request $request)
    {

        try {
            $calendarEvent = Calendar::findOrFail($id);
            $calendarEvent = $calendarEvent->update([
                'summary' => $request->summary,
                'frequency' => $request->frequency,
                'starts' => $request->starts
    
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Wydarzenie zostalo zaktualizowane',
                'data' => $calendarEvent
            ], 200);
        } catch (\Throwable $th) {
            return ['error'=>$th];
        }
       
      
    }

    public function destroy($id)
    {
        try {
            $event = Calendar::find($id);
        
            $event->delete();
            return ['message' => 'Event removed successfully'];
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}
