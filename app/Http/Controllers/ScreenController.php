<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Screen;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    function __construct()

    {
        $this->middleware('verified');
        $this->middleware('permission:client-list|admin-list', ['only' => ['screenUno', 'screenDos']]);
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
        $data_gen = [
            'prev_url' => '/',
            'title' => 'Sube tu fotos o videos y publica con nosotros.'
        ];

        $prices = config('price-list.PRICE_LIST');

        if (isset(auth()->user()->discounts) && auth()->user()->discounts > 0) {
            $descuento = auth()->user()->discounts;
            foreach ($prices as $key => $value) {
                $prices[$key]['amount'] = $value['amount'] - ($value['amount'] * ($descuento / 100));
            }
        }

        return view('pantalla1', compact('id', 'screen', 'prices', 'data_gen'));
    }

    public function screenDos(Request $request)
    {

        // return $request;
        $rutaLocal = [];
        $arr = [];

        $data_gen = [
            'prev_url' => route('pantalla1', $request->screen_id),
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];

        $time = $request->tiempo;

        $id = $request->screen_id;
        $media_id = $request->media_id;
        $datas = Media::getDataMedia($media_id);
        $prices = config('price-list.PRICE_LIST');

        if (isset(auth()->user()->discounts) && auth()->user()->discounts > 0) {
            $descuento = auth()->user()->discounts;
            foreach ($prices as $key => $value) {
                $prices[$key]['amount'] = $value['amount'] - ($value['amount'] * ($descuento / 100));
            }
        }

        $matchingPrices = array_filter($prices, function ($price) use ($time) {
            return $price['seconds'] == $time;
        });

        $datas->prices = reset($matchingPrices);


        foreach (json_decode($datas->files_name, true) as $file_name) {
            $url = pathinfo($file_name['file_name']);
            $extension = $url['extension'];
            $extension = strtok($extension, '?');
            $arr[] = $extension;
            $rutaLocal[] = 'storage/uploads/tmp/' . $file_name['file_name'];
        }




        return view('pantalla2', compact('id', 'time', 'rutaLocal', 'media_id', 'arr', 'datas', 'data_gen'));
    }
}
