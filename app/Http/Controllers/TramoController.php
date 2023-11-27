<?php

namespace App\Http\Controllers;

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
        // return response()->json($request, 200);
        $data = $this->tramo->getTramos($request->fecha, $request->duration, $request->screen_id);
        return response()->json($data, 200);
    }


    public function getAvailabilityDates(Request $request)
    {
        $data = $this->tramo->getAvailabilityDates($request->screen_id, $request->duration);
        return response()->json($data, 200);
    }
}
