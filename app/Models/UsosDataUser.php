<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsosDataUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'usos_data_id'];
    public $timestamps = false;
}
