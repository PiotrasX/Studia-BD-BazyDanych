<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CechyPojazdu extends Model
{
    protected $table = 'cechy_pojazdu';
    protected $primaryKey = 'id_cechy_pojazdu';
    public $timestamps = false;

    protected $fillable = ['id_cechy_pojazdu', 'marka', 'model', 'nadwozie'];
}
