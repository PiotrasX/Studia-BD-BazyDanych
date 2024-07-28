<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pracownik extends Model
{
    protected $table = 'pracownicy';
    protected $primaryKey = 'id_pracownika';
    public $timestamps = false;

    protected $fillable = ['id_pracownika', 'id_konta', 'stanowisko'];

    public function konto()
    {
        return $this->belongsTo(Konto::class, 'id_konta', 'id_konta');
    }
}
