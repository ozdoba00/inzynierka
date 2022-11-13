<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeInformation extends Model
{
    use HasFactory;
    public $table = 'home_informations';
    protected $fillable = [
        'content', 'user_id'
    ];


    public function user()
    {
        return $this-> belongsTo('App\Models\User');
    }
}
