<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    function __construct()

    {
        $this->middleware('verified');
        $this->middleware('permission:admin-list|admin-create|admin-edit|admin-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data = Screen::all();
        return view('screen.index', compact('data'));
    }

    public function create()
    {
        return view('screen.create');
    }
    public function store(Request $request)
    {

        $storeData = new Screen();

        $storeData->name = strtoupper($request->name);
        $storeData->location = strtoupper($request->location);
        // $storeData->price = strtoupper($request->price);
        $storeData->save();

        return  redirect()->route('screen.index');
    }
    public function edit($id)
    {
        $data = Screen::find($id);


        return view('screen.edit', compact('data'));
    }
    public function update(Request $request, $id)
    {
        $updateData = Screen::find($id);

        $updateData->name = strtoupper($request->name);
        $updateData->location = strtoupper($request->location);
        $updateData->price = strtoupper($request->price);
        $updateData->save();

        return  redirect()->route('screen.index');
    }
    public function show($id)
    {
        $data = Screen::find($id);


        return view('screen.show', compact('data'));
    }


    public function screenUno($id)
    {
        $screen = Screen::find($id);


        $prices = config('price-list.PRICE_LIST');

        if (isset(auth()->user()->discounts) && auth()->user()->discounts > 0) {
            $descuento = auth()->user()->discounts;
            foreach ($prices as $key => $value) {
                $prices[$key]['amount'] = $value['amount'] * ($descuento / 100);
            }
        }



        return view('pantalla1', compact('id', 'screen', 'prices'));
    }

    public function screenDos($id, $time, $media_id, $preference_id)
    {
        $data = new MediaController();
        $datas = $data::getDataMedia($media_id);


        $prices = config('price-list.PRICE_LIST');

        if (isset(auth()->user()->discounts) && auth()->user()->discounts > 0) {
            $descuento = auth()->user()->discounts;
            foreach ($prices as $key => $value) {
                $prices[$key]['amount'] = $value['amount'] * ($descuento / 100);
            }
        }

        $matchingPrices = array_filter($prices, function ($price) use ($time) {
            return $price['seconds'] == $time;
        });

        $datas->prices = reset($matchingPrices);



        $rutaLocal = [];
        $arr = [];
        foreach (json_decode($datas->files_name, true) as $file_name) {
            $url = pathinfo($file_name['file_name']);
            $extension = $url['extension'];
            $extension = strtok($extension, '?');
            $arr[] = $extension;
            $rutaLocal[] = 'storage/uploads/tmp/' . $file_name['file_name'];
        }


        return view('pantalla2', compact('id', 'time', 'rutaLocal', 'media_id', 'arr', 'preference_id', 'datas'));
    }
}
