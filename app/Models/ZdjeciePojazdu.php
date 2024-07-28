<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZdjeciePojazdu extends Model
{
    protected $table = 'zdjecia_pojazdow';
    protected $primaryKey = 'id_zdjecia';
    public $timestamps = false;

    protected $fillable = ['id_zdjecia', 'id_pojazdu', 'nazwa_zdjecia'];

    public function pojazd()
    {
        return $this->belongsTo(Pojazd::class, 'id_pojazdu', 'id_pojazdu');
    }
}
