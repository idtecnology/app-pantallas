<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    function __construct()

    {
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
        $storeData->price = strtoupper($request->price);
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
}
