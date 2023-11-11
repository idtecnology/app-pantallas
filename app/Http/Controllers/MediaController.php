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

        $check = Media::select('duration', 'time', 'screen_id')->where('time', '=', $request->time)->where('date', '=', $request->date)->get();

        if (count($check) > 0) {
            $hora_db = $check[0]->time;
            $total_db = strtotime('+' . $check[0]->duration . 'second', strtotime($hora_db));
            $total_db = date('H:i:s', $total_db);


            $hora_n = $request->time;
            $total_n = strtotime('+' . $request->duration . 'second', strtotime($hora_n));
            $total_n = date('H:i:s', $total_n);

            if ($total_db == $total_n && $request->screen_id == $check[0]->screen_id) {
                return false;
            }
        } else {

            $saveMedia = new Media();
            $saveMedia->client_id = auth()->id();
            $saveMedia->screen_id = $request->screen_id;
            $saveMedia->name = $request->name;
            $saveMedia->time = $request->time;
            $saveMedia->date = $request->date;
            $saveMedia->duration = $request->duration;
            $saveMedia->type = $request->type;

            if ($request->type == 1) {
                //video
                //upload local storage
                $fileName = auth()->id() . '_' . time() . '.' . $request->file('files')[0]->extension();
                $request->file('files')[0]->storeAs('/video/uploads/' . date('Ymd'), $fileName, 'public');
                $saveMedia->path = '/video/uploads/' . date('Ymd');

                //Upload to s3
                $path = Storage::disk('s3')->put($request->screen_id . '/' . date('Ymd') . '/', $request->file('files')[0]);
                $path = Storage::disk('s3')->url($path);

                $saveMedia->files_name = $fileName;
            } else {
                //Slideshow
                $files = [];
                if ($request->file('files')) {
                    foreach ($request->file('files') as $key => $file) {
                        $file_name = auth()->id() . '_' . $key . '_' . time() . '.' . $file->extension();
                        $file->storeAs('/slideshow/uploads/' . date('Ymd'), $file_name, 'public');

                        //Upload to s3
                        // $path = Storage::disk('s3')->put(date('Ymd').'/slideshow', 'images_' . $key . '_'$request->file('files'));
                        // $path = Storage::disk('s3')->url($path);


                        $files[] = $file_name;
                        $saveMedia->path = '/slideshow/uploads/' . date('Ymd');
                        $saveMedia->files_name = json_encode($files);
                    }
                }
            }
            $saveMedia->save();
            return  redirect()->route('sale.index');
        }
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
