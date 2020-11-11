<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'slug', 'details','meta_tag','meta_description','storename'];
    public $timestamps = false;
}
