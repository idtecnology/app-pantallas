<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Screen;
use finfo;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        $data = Media::all();
        return view('media.index', compact('data'));
    }

    public function create()
    {

        $pos = Screen::pluck('name', '_id');
        return view('media.create', compact('pos'));
    }
    public function store(Request $request)
    {
    }
    public function edit($id)
    {
        $data = Media::find($id);
        return view('media.edit', compact('data'));
    }
    public function update(Request $request)
    {
    }

    public function show($id)
    {
    }
}
