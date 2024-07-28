<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WystawionyPojazdSprzedaz extends Model
{
    protected $table = 'wystawione_pojazdy_sprzedaz';
    protected $primaryKey = 'id_ogloszenia';
    public $timestamps = false;

    protected $fillable = ['id_ogloszenia', 'id_pojazdu', 'data_wystawienia', 'status_ogloszenia', 'data_zakonczenia'];
}
