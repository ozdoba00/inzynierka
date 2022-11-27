<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Offer;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class AnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $announcements = Announcement::where('recipient_id', Auth::user()->id)->with(['user', 'offer'])->get();

        foreach ($announcements as $announcement) {
            $announcement['user']['profile_image'] =  $announcement['user']['profile_image'] ? Storage::url( $announcement['user']['profile_image']) :  $announcement['user']['profile_image'];

        }

        return ['announcements' => $announcements];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->type === 'O') 
        {
            $offer = new Offer();
            $offer = $offer->with('user')->where('id', $request->offerId)->orderBy('updated_at', 'DESC')->first();

            $announcement = Announcement::where([['sender_id', Auth::user()->id], ['offer_id', $offer->id]])->first();

            if (!empty($announcement)) 
            {
                return ['status' => true, 'message' => 'Announcement has already been sent'];
            } 
            else 
            {
                Announcement::create([
                    'recipient_id' => $offer->user->id,
                    'sender_id' => Auth::user()->id,
                    'offer_id' => $offer->id,
                    'type' => 'O'
                ]);

                return ['status' => true, 'message' => 'Announcement has been sent'];

            }
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $announcement = Announcement::find($id);
            if ($announcement->recipient_id == Auth::user()->id) {
                $announcement->delete();
                return ['message' => 'Announcement removed successfully'];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
