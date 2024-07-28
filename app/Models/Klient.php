<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klient extends Model
{
    protected $table = 'klienci';
    protected $primaryKey = 'id_klienta';
    public $timestamps = false;

    protected $fillable = ['id_klienta', 'id_konta', 'stan_konta'];

    public function konto()
    {
        return $this->belongsTo(Konto::class, 'id_konta', 'id_konta');
    }
}
