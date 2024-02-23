<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use Illuminate\Http\Request;

class ScreenController extends Controller
{

    public function getScreens()
    {
        $screens = Screen::paginate(20);
        return response()->json(['data' => $screens], 200);
    }
    public function getScreen($id)
    {
        $screen = Screen::find($id);
        return response()->json(['data' => $screen], 200);
    }
}
