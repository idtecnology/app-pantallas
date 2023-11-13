<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Screen;
use finfo;
use Illuminate\Contracts\Cache\Store;
// use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    function __construct()

    {
        $this->middleware('permission:client-list|client-create|client-edit|client-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:client-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:client-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client-delete', ['only' => ['destroy']]);
    }
    public function index()
    {

        $user = auth()->user();

        if ($user->givePermissionTo('admin-list')) {
            $data = Media::all();
        } else {
            $data = Media::where('client_id', '=', auth()->id())->get();
        }

        return view('media.index', compact('data'));
    }

    public function create()
    {

        $pos = Screen::pluck('name', '_id');
        return view('media.create', compact('pos'));
    }
    public function store(Request $request)
    {
        $saveMedia = new Media();
        $saveMedia->client_id = auth()->id();
        $saveMedia->screen_id = 1;
        $saveMedia->name = $request->name;
        $saveMedia->time = 15;
        $saveMedia->duration = 200;
        $saveMedia->date = date('Y-m-d');
        $saveMedia->type = $request->type;

        $archivos = '';

        if ($request->type == 1) {
            $saveMedia->files_name = auth()->id() . '_' . time() . '.' . $request->file('files')[0]->extension();

            $path = Storage::disk('s3')->put('1/' . date('Ymd') . '/', $request->file('files')[0]);
            $path = Storage::disk('s3')->url($path);

            $saveMedia->path =  $path;

            $archivos = $path;
        } else {
            $files = [];
            foreach ($request->file('files') as $key => $file) {
                $file_name = auth()->id() . '_' . $key . '_' . time() . '.' . $file->extension();
                $file->storeAs('/slideshow/uploads/' . date('Ymd'), $file_name, 'public');
                $files[] = $file_name;
                $saveMedia->path = '/slideshow/uploads/' . date('Ymd');
                $saveMedia->files_name = json_encode($files);
            }
        }



        $saveMedia->save();

        return response()->json($path, 200);
        // }
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


    public function programar($id)
    {

        return $id;
    }
}
