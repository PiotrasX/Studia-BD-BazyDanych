<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dane extends Model
{
    protected $table = 'dane';
    protected $primaryKey = 'id_danych';
    public $timestamps = false;

    protected $fillable = ['id_danych', 'imie', 'nazwisko', 'numer_telefonu', 'email', 'ulica', 'numer_domu', 'kod_pocztowy', 'miejscowosc', 'kraj'];
}
