<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    protected $fillable = [
        'campania_id',
        'client_id',
        'tramo_id',
        'screen_id',
        'pago_id',
        'preference_id',
        'time',
        'date',
        'duration',
        'files_name',
        'approved',
        'isPaid',
        'reproducido',
        'isActive',
    ];

    public static function getDataMedia($id)
    {
        return self::find($id);
    }
}
