<?php

namespace App\Http\Controllers;

use App\Models\Tramo;
use Illuminate\Http\Request;

class TramoController extends Controller
{
    public function index(Request $request)
    {

        if ($request->limit != '') {
            // return date('H:i');
            $data = Tramo::where('tramos', '>=', date('H:i'))->where('fecha', '>=', $request->fecha)->where('duracion', '>', 15)->limit(6)->get();
        } else {
            $data = Tramo::where('tramos', '>=', date('H:i'))->where('fecha', '>=', $request->fecha)->where('fecha', '<=', date('Y-m-d'))->where('duracion', '>', 15)->get();
        }
        return response()->json($data, 200);
    }
}
