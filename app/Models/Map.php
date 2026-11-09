<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $table = 'map';

    public $timestamps = false;

    protected $fillable = ['nom', 'jeu', 'image', 'description'];
}
