<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SprzedanyPojazd extends Model
{
    protected $table = 'sprzedane_pojazdy';
    protected $primaryKey = 'id_sprzedazy';
    public $timestamps = false;

    protected $fillable = ['id_sprzedazy', 'id_pojazdu', 'id_kupujacego', 'data_sprzedazy'];
}
