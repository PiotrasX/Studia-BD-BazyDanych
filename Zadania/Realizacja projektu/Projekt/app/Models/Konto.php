<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Konto extends Authenticatable
{
    use Notifiable;

    protected $table = 'konta';
    protected $primaryKey = 'id_konta';
    public $timestamps = false;

    protected $fillable = ['id_konta', 'login', 'haslo', 'typ_konta', 'id_danych'];
    protected $hidden = ['haslo'];

    public function getAuthPassword()
    {
        return $this->haslo;
    }

    public function dane()
    {
        return $this->belongsTo(Dane::class, 'id_danych', 'id_danych');
    }
}
