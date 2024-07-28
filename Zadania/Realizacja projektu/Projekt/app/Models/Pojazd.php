<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pojazd extends Model
{
    protected $table = 'pojazdy';
    protected $primaryKey = 'id_pojazdu';
    public $timestamps = false;

    protected $fillable = [
        'id_pojazdu', 'vin', 'id_cechy_pojazdu', 'rok_produkcji', 'przebieg', 'pojemnosc_silnika', 'moc_silnika',
        'rodzaj_paliwa', 'liczba_drzwi', 'liczba_miejsc', 'cena', 'id_wlasciciela', 'status_pojazdu'
    ];

    public function zdjecia()
    {
        return $this->hasMany(ZdjeciePojazdu::class, 'id_pojazdu');
    }

    public function cechyPojazdu()
    {
        return $this->belongsTo(CechyPojazdu::class, 'id_cechy_pojazdu');
    }
}
