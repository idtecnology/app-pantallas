<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $fillable = [
        'nombre',
        'direccion',
        'imagen',
        'hora_encendido',
        'horario_apagado',
        'url_google_maps',
        'proximo_horario_disponible',
        'ultimo_dia_compra',
        'aspect_ratio',
        'dimension_px',
        'dimension_mts_marco',
        'dimension_mts_pantalla'
    ];
}
