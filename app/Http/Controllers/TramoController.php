<?php

namespace App\Http\Controllers;

use App\Models\Tramo;
use Illuminate\Http\Request;

class TramoController extends Controller
{
    public function index(Request $request)
    {

        if ($request->limit != '') {

            $data = Tramo::where('tramos', '>=', date('H:i'))->where('fecha', '=', $request->fecha)->where('duracion', '>', 15)->limit(6)->get();
        } else {
            if ($request->fecha === date('Y-m-d')) {
                $data = Tramo::where('tramos', '>=', date('H:i'))->where('fecha', '=', $request->fecha)->where('duracion', '>', 15)->get();
            } else {
                $data = Tramo::where('fecha', '=', $request->fecha)->where('duracion', '>', 15)->get();
            }
        }
        return response()->json($data, 200);
    }


    public function fechasDisp()
    {

        $data = Tramo::where('duracion', '>', 15)->limit(6)->get();

        return response()->json($data, 200);
    }
}
