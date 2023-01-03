<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Offer extends Model
{
    use HasFactory;


    protected $fillable = [
        'content', 'price', 'user_id', 'show_type', 'study_field_id'
    ];

    public function user()
    {
        return $this-> belongsTo('App\Models\User');
    }


    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:00 ',
    ];


    public static function saveFavourite($userId, $offerId)
    {
        DB::table('offer_likes')
        ->insert([
            'user_id' => $userId,
            'offer_id' => $offerId
        ]);
    }

    public static function checkOfferFavourites($userId, $offerId)
    {
        return DB::table('offer_likes')
        ->join('offers', 'offer_likes.offer_id', '=', 'offers.id')
        ->join('users', 'offer_likes.user_id', '=', 'users.id')
        ->select('offer_likes.*')
        ->where([['offer_likes.offer_id', $offerId], ['offer_likes.user_id', $userId]])
        ->first();
    }

    public static function removeFavourite($favId)
    {
        DB::table('offer_likes')
        ->where('id', $favId)
        ->delete();
    }
}
