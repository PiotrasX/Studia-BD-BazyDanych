<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerwisowanyPojazd extends Model
{
    protected $table = 'serwisowane_pojazdy';
    protected $primaryKey = 'id_serwisu';
    public $timestamps = false;

    protected $fillable = ['id_serwisu', 'id_pracownika', 'id_pojazdu', 'opis_usterki', 'data_poczatku_serwisu', 'status_serwisu', 'data_konca_serwisu'];
}
