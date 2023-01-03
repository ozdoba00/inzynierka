<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OfferRequest;

use Illuminate\Support\Facades\Storage;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Offer $offer, Request $request)
    {
        if(!empty($request->filter))
        {
            $offers = $offer->with('user')->where('study_field_id', '=', $request->filter)->orderBy('updated_at', 'DESC')->get();
        }
        else
        {
            $offers = $offer->with('user')->orderBy('updated_at', 'DESC')->get();
        }

        foreach ($offers as $key => $offerData) {

            if(Offer::checkOfferFavourites(Auth::user()->id, $offerData['id']))
            {
                $offerData['fav'] = true;
            }
            $offers[$key]['editable'] = $offerData['user']['id'] == Auth::user()->id ? true : false;
            
        }
        return ['offers'=>$offers];
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
     * @param  \Illuminate\Http\OfferRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferRequest $request)
    {
        $selectedStudyField = null;
        if($request->studyField)
        {
            $selectedStudyField = $request->studyField;
        }
        try {
            Offer::create([
                'content' => $request->content,
                'price' => $request->price,
                'user_id' => auth()->id(),
                'show_type' => $request->filter,
                'study_field_id' => $selectedStudyField
            ]);
            return response()->json(['message' => 'Offer has been added successfully'], 200); 
        } catch (\Throwable $th) {
            return $th;
        }
        
    }


    public function setFavourite($id)
    {
        try {
            $userId = Auth::user()->id;

            $offerFav = Offer::checkOfferFavourites($userId, $id);

            if (!empty($offerFav)) {
                Offer::removeFavourite($offerFav->id);
                return ['status' => true, 'removed' => true];
            } else {
                Offer::saveFavourite($userId, $id);
                return ['status' => true, 'removed' => false];
            }
        } catch (\Throwable $th) {
            return ['error' => $th];
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
            $offer = Offer::find($id);
        if($offer->user_id == Auth::user()->id)
        {
            $offer->delete();
            return ['message'=> 'Offer removed successfully'];
        }

        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
