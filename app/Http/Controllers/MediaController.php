<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Screen;
use App\Models\Tramo;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Laravel\Ui\Presets\React;

class MediaController extends Controller
{
    function __construct()

    {
        $this->middleware('permission:admin-list|admin-create|admin-edit|admin-delete|client-list|client-create|client-edit|client-delete', ['only' => ['index', 'show', 'grilla']]);
        $this->middleware('permission:admin-create|client-create', ['only' => ['create', 'store', 'grilla']]);
        $this->middleware('permission:admin-edit|client-edit', ['only' => ['edit', 'update', 'grilla']]);
        $this->middleware('permission:admin-delete|client-delete', ['only' => ['destroy', 'grilla']]);
    }
    public function index()
    {


        $user = auth()->user();

        if ($user->givePermissionTo('admin-list')) {
            $data = Media::where('isPaid', '=', 1)->get();
        } else {
            $data = Media::where('client_id', '=', auth()->id())->get();
        }

        return view('media.index', compact('data'));
    }

    public function create()
    {
        // $data = Tramo::where('tramos', '>=', date('H:i'))->where('fecha', '>=', $request->fecha)->where('fecha', '<=', date('Y-m-d'))->where('duracion', '>', 15)->get();
        $pos = Screen::pluck('name', '_id');
        return view('media.create', compact('pos'));
    }
    public function store(Request $request)
    {
        // return $request;

        $arr = [];


        if (is_array($request->tramos)) {
            foreach ($request->tramos as $value) {
                $tramo = Tramo::where('fecha', '=', $request->fecha)->where('tramos', '=', $value)->get();
                if ($tramo[0]->duracion > $request->duration) {
                    $resto = $tramo[0]->duracion - $request->duration;
                    Tramo::where('_id', '=', $tramo[0]->_id)->update(['duracion' => $resto]);
                    $saveMedia = new Media();
                    $saveMedia->client_id = auth()->id();
                    $saveMedia->screen_id = 1;
                    $saveMedia->name = strtoupper($request->name);
                    $saveMedia->time = $value;
                    $saveMedia->tramo_id = $tramo[0]->tramo_id;
                    $saveMedia->duration = $request->duration;
                    $saveMedia->date = $request->fecha;
                    $saveMedia->type = $request->type;
                    $saveMedia->approved = 1;
                    // ! Ver que solo suba una vez
                    // ! de una

                    if ($request->type == 1) {
                        $saveMedia->files_name = auth()->id() . '_' . time() . '.' . $request->file('files')[0]->extension();
                        //Colocar tramo
                        $path = Storage::disk('s3')->put('1/' . date('Ymd'), $request->file('files')[0]);
                        $path = Storage::disk('s3')->url($path);
                        $ath = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(30));

                        $saveMedia->path =  $path;
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

                    // return response()->json(['message' => 'success', 'status' => 1, 'img' => $ath], 200);

                }
            }
            return redirect()->route('sale.index');
        } else {
            $tramo = Tramo::where('fecha', '=', $request->fecha)->where('tramos', '=', $request->tramo_select)->get();


            if ($tramo[0]->duracion > $request->duration) {
                $resto = $tramo[0]->duracion - $request->duration;
                Tramo::where('_id', '=', $tramo[0]->_id)->update(['duracion' => $resto]);
                $saveMedia = new Media();
                $saveMedia->client_id = auth()->id();
                $saveMedia->screen_id = $request->screen_id;
                $saveMedia->name = strtoupper($request->name);
                $saveMedia->time = $request->tramo_select;
                $saveMedia->duration = $request->duration;
                $saveMedia->date = $request->fecha;
                $saveMedia->type = $request->type;
                $saveMedia->isPaid = 1;
                $saveMedia->tramo_id = $tramo[0]->tramo_id;

                if ($request->type == 1) {
                    $saveMedia->files_name = auth()->id() . '_' . time() . '.' . $request->file('files')[0]->extension();
                    //Colocar tramo
                    $path = Storage::disk('s3')->put('1/' . date('Ymd'), $request->file('files')[0]);
                    $path = Storage::disk('s3')->url($path);
                    $ath = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(30));

                    $saveMedia->path =  $path;
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

                return response()->json(['message' => 'success', 'status' => 1, 'img' => $ath], 200);
            } else {
                return response()->json(['message' => 'el tramo no cuenta con el tiempo disponible', 'status' => 0], 200);
            }
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
        $data = Media::find($id);
        // return $data;
        return view('media.show', compact('data'));
    }


    public function programar($id)
    {

        return $id;
    }


    public function grilla()
    {

        $data = Media::where('date', '=', date('Y-m-d'))->where('approved', '=', 1)->where('isPaid', '=', 1)->get();

        // return $data;
        return view('media.grilla', compact('data'));
    }


    //! MONTAR EL APROVAR DE UNA.
}
