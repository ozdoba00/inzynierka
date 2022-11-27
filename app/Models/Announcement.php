<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'sender_id',
        'offer_id',
        'type'
    ];

    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:00 ',
    ];
    public function user()
    {
        return $this-> belongsTo('App\Models\User', 'sender_id');
    }

    public function offer()
    {
        return $this-> belongsTo('App\Models\Offer', 'offer_id');
    }
}
