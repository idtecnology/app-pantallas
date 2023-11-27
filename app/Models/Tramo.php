<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramo extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';


    public function getTramos(string $date, int $duration, int $screen_id)
    {
        return $this::where('fecha', '=', $date)
            ->where('duracion', '>=', $duration)
            ->where('screen_id', '=', $screen_id)
            ->whereRaw("TIMEDIFF(CONCAT(fecha, ' ', tramos), NOW()) > '00:10:00'")
            ->get();
    }

    public function getAvailabilityDates(int $screen_id, int $duration)
    {
        return $this::select('fecha')
            ->where('fecha', '>=', date('Y-m-d'))
            ->where('duracion', '>=', $duration)
            ->where('screen_id', '=', $screen_id)
            ->whereRaw("TIMEDIFF(CONCAT(fecha, ' ', tramos), NOW()) > '00:10:00'")
            ->groupBy('fecha')
            ->get();
    }
}
