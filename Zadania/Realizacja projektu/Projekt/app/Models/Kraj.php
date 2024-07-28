<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kraj extends Model
{
    protected $table = 'kraje';
    protected $primaryKey = 'id_kraju';
    public $timestamps = false;

    protected $fillable = ['id_kraju', 'nazwa'];
}
