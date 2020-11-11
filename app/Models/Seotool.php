<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seotool extends Model
{
    protected $fillable = ['google_analytics','meta_keys','storename','pixel'];
    public $timestamps = false;
}
