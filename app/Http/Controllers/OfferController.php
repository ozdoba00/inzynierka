<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OfferRequest;

use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Offer $offer)
    {
        $offers = $offer->with('user')->get();

        foreach ($offers as $key => $offerData) {

            // return ['user'=> Auth::user()->id];
            if ($offerData['user']['id'] == Auth::user()->id) {
                $offers[$key]['editable'] = true;
            } else {
                $offers[$key]['editable'] = false;
            }
            
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
    public function store(OfferRequest $request, Offer $offer)
    {
        $offerData = $request->all();
        
        Offer::create([
            'content' => request('content'),
            'price' => request('price'),
            'user_id' => auth()->id()
        ]);
        return ['offerData'=>$offerData];
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
        //
    }
}
