<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campania extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $fillable = [
        'name',
        'screen_id',
        'fecha_inicio',
        'fecha_fin',
        'hora_inicio',
        'hora_fin',
        'cant',
    ];
}
