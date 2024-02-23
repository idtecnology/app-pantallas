<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tramo;
use Illuminate\Http\Request;

class TramoController extends Controller
{
    private $tramo;
    public function __construct(Tramo $tramo)
    {
        $this->tramo = $tramo;
    }
    public function index(Request $request)
    {
        $data = $this->tramo->getTramos($request->fecha, $request->duration, $request->screen_id);
        if ($data) {
            return response()->json(['data' => $data], 200);
        } else {
            return response()->json(['message' => 'No hay tramos disponibles'], 404);
        }
    }


    public function getAvailabilityDates(Request $request)
    {
        $data = $this->tramo->getAvailabilityDates($request->screen_id, $request->duration);
        if ($data) {
            return response()->json(['data' => $data], 200);
        } else {
            return response()->json(['message' => 'No hay fechas disponibles'], 404);
        }
    }
}
