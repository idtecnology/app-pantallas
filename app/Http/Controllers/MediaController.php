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
use Illuminate\Support\Arr;
use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;
use Illuminate\Support\Facades\Session;

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
        if ($user->can('admin-list')) {
            $data = Media::select('media.*', 'users.email')->where('isPaid', '=', 1)->join('users', 'users.id', '=', 'media.client_id')->get();
        } else {
            $data = Media::where('client_id', '=', $user->id)->get();
        }
        return view('media.index', compact('data'));
    }

    public function create()
    {
        // $data = Tramo::where('tramos', '>=', date('H:i'))->where('fecha', '>=', $request->fecha)->where('fecha', '<=', date('Y-m-d'))->where('duracion', '>', 15)->get();
        $pos = Screen::pluck('name', '_id');
        return view('media.create', compact('pos'));
    }


    public function guardarData(Request $request)
    {
        switch ($request->tiempo) {
            case '15':
                $price = 10000;
                break;
            case '30':
                $price = 20000;
                break;
            case '45':
                $price = 30000;
                break;
            case '60':
                $price = 40000;
                break;
            case '120':
                $price = 80000;
                break;
        }

        SDK::setAccessToken(env('MP_SECRET'));



        // Instanciamos la preferencia: 
        $preference = new Preference();
        $payment_methods = [
            'excluded_payment_methods' => [['id' => 'argencard'], ['id' => 'cabal'], ['id' => 'cmr'], ['id' => 'cencosud'], ['id' => 'cordobesa'], ['id' => 'naranja'], ['id' => 'tarshop'], ['id' => 'debcabal']],
            'excluded_payment_types' => [['id' => 'ticket']],
            'installments' => 1,
        ];
        $back_urls = [
            'success' => route('success'),
            'failure' => route('failure'),
            'pending' => route('pendiente'),
        ];

        $preference->payment_methods = $payment_methods;
        $preference->back_urls = $back_urls;

        // Instanciamos el item
        $item = new Item();
        $item->title = 'Producto';
        $item->quantity = 1;
        $item->unit_price = $price;
        $preference->items = [$item];
        $preference->save();

        $files_names = [];

        $archivos = $request->file('archivos');
        if ($request->media_id > 0) {
            //Actualizamos los que estan
            $media_update = Media::where('_id', '=', $request->media_id)->get()[0];
            if (is_object(json_decode($media_update->files_name, true)) || is_array(json_decode($media_update->files_name, true))) {
                $imgs_temp = json_decode($media_update->files_name, true);
                foreach ($imgs_temp as $img_tmp) {
                    $rutaLocal = storage_path('app/public/uploads/tmp/' . $img_tmp);
                    unlink($rutaLocal);
                }
            } else {
                $rutaLocal = storage_path('app/public/uploads/tmp/' . $media_update->files_name);
                unlink($rutaLocal);
            }
            foreach ($archivos as $archivo) {
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $archivo->storeAs('public/uploads/tmp/', $nombreArchivo);
                $files_names[] = $nombreArchivo;
            }
            if (count($archivos) > 1) {
                Media::where('_id', '=', $request->media_id)->update(['files_name' => json_encode($files_names)]);
            } else {
                Media::where('_id', '=', $request->media_id)->update(['files_name' => $files_names[0]]);
            }
            return response()->json(['mensaje' => 'éxito', 'preference_id' => $preference->id]);
        } else {
            //insertamos nuevos. 
            $media = new Media();
            foreach ($archivos as $archivo) {
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $archivo->storeAs('public/uploads/tmp', $nombreArchivo);
                $files_names[] = $nombreArchivo;
            }
            if (count($archivos) > 1) {
                $media->files_name = json_encode($files_names);
            } else {
                $media->files_name = $files_names[0];
            }
            $media->screen_id = $request->screen_id;
            $media->duration = $request->tiempo;
            $media->client_id = $request->client_id;
            $media->preference_id = $preference->id;
            $media->save();

            return response()->json(['mensaje' => 'Archivos guardados con éxito', 'media_id' => $media->_id, 'preference_id' => $preference->id]);
        }
    }


    public function store(Request $request)
    {
        $extensionesPermitidas = ['jpeg', 'png', 'jpg', 'mp4'];
        $tramo = Tramo::where('fecha', '=', $request->fecha)->where('screen_id', '=', $request->screen_id)->where('tramos', '=', $request->tramo_select)->get();

        if ($request->media_id != '') {
            $dataUpdateMedia = Media::find($request->media_id);
            if (is_array($request->file('file'))) {
                if (count($request->file('file')) > 1) {
                    if ($tramo[0]->duracion > $request->duration) {
                        $resto = $tramo[0]->duracion - $request->duration;
                        Tramo::where('_id', '=', $tramo[0]->_id)->update(['duracion' => $resto]);
                        $dataUpdateMedia->screen_id = $request->screen_id;
                        $dataUpdateMedia->time = $request->tramo_select;
                        $dataUpdateMedia->duration = $request->duration;
                        $dataUpdateMedia->date = $request->fecha;
                        $dataUpdateMedia->tramo_id = $tramo[0]->tramo_id;
                        if (is_object(json_decode($dataUpdateMedia->files_name, true)) || is_array(json_decode($dataUpdateMedia->files_name, true))) {
                            $imgs_temp = json_decode($dataUpdateMedia->files_name, true);
                            foreach ($imgs_temp as $img_tmp) {
                                $rutaLocal = storage_path('app/public/uploads/tmp/' . $img_tmp);
                                unlink($rutaLocal);
                            }
                        } else {
                            $rutaLocal = storage_path('app/public/uploads/tmp/' . $dataUpdateMedia->files_name);
                            unlink($rutaLocal);
                        }
                        $files_names = [];
                        foreach ($request->file('file') as $file) {
                            $extension = $file->getClientOriginalExtension();
                            if (in_array($extension, $extensionesPermitidas)) {
                                $path = Storage::disk('s3')->put($request->screen_id . '/' . date('Ymd', strtotime($request->fecha)), $file);
                                $path = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(1440));
                                $files_names[] = $path;
                            } else {
                                return "manejar error";
                            }
                        }
                        $dataUpdateMedia->files_name = json_encode($files_names);
                        $dataUpdateMedia->save();
                        $preference = $request->preference;
                        return redirect()->route('pagare', ['preference' => $preference]);
                    } else {
                        return 'tengo que manejar el mensaje de error.';
                    }
                } else {
                    $extension = $request->file('file')[0]->getClientOriginalExtension();
                    if (in_array($extension, $extensionesPermitidas)) {
                        if ($tramo[0]->duracion > $request->duration) {
                            $resto = $tramo[0]->duracion - $request->duration;
                            Tramo::where('_id', '=', $tramo[0]->_id)->update(['duracion' => $resto]);
                            $dataUpdateMedia->screen_id = $request->screen_id;
                            $dataUpdateMedia->time = $request->tramo_select;
                            $dataUpdateMedia->duration = $request->duration;
                            $dataUpdateMedia->date = $request->fecha;
                            $dataUpdateMedia->tramo_id = $tramo[0]->tramo_id;
                            $path = Storage::disk('s3')->put($request->screen_id . '/' . date('Ymd', strtotime($request->fecha)), $request->file('file')[0]);
                            $path = Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(1440));
                            $dataUpdateMedia->files_name = $path;
                            $dataUpdateMedia->save();
                            $preference = $request->preference;
                            return redirect()->route('pagare', ['preference' => $preference]);
                        }
                    } else {
                        return 'mensaje de error';
                    }
                }
            } else {
                $tramo = Tramo::where('fecha', '=', $request->fecha)->where('screen_id', '=', $request->screen_id)->where('tramos', '=', $request->tramo_select)->get();
                if ($tramo[0]->duracion > $request->duration) {
                    $resto = $tramo[0]->duracion - $request->duration;
                    Tramo::where('_id', '=', $tramo[0]->_id)->update(['duracion' => $resto]);
                    $dataUpdateMedia->screen_id = $request->screen_id;
                    $dataUpdateMedia->time = $request->tramo_select;
                    $dataUpdateMedia->duration = $request->duration;
                    $dataUpdateMedia->date = $request->fecha;
                    $dataUpdateMedia->tramo_id = $tramo[0]->tramo_id;
                    if (is_object(json_decode($dataUpdateMedia->files_name, true)) || is_array(json_decode($dataUpdateMedia->files_name, true))) {
                        $imgs_temp = json_decode($dataUpdateMedia->files_name, true);
                        foreach ($imgs_temp as $img_tmp) {
                            $rutaLocal = storage_path('app/public/uploads/tmp/' . $img_tmp);
                            $name_file = $request->screen_id . '/' . date('Ymd', strtotime($request->fecha)) . '/' . $img_tmp;
                            $nameF = '/' . date('Ymd', strtotime($request->fecha)) . '/' . $img_tmp;
                            $path = Storage::disk('s3')->put($name_file, file_get_contents($rutaLocal));
                            $path = Storage::disk('s3')->temporaryUrl($path . $nameF, now()->addMinutes(1440));
                            $files_names[] = $path;
                            unlink($rutaLocal);
                        }
                    } else {
                        $rutaLocal = storage_path('app/public/uploads/tmp/' . $dataUpdateMedia->files_name);
                        $name_file = $request->screen_id . '/' . date('Ymd', strtotime($request->fecha)) . '/' . $dataUpdateMedia->files_name;
                        $nameF = '/' . date('Ymd', strtotime($request->fecha)) . '/' . $dataUpdateMedia->files_name;
                        $path = Storage::disk('s3')->put($name_file, file_get_contents($rutaLocal));
                        $path = Storage::disk('s3')->temporaryUrl($path . $nameF, now()->addMinutes(1440));
                        unlink($rutaLocal);
                    }
                    $dataUpdateMedia->files_name = $path;
                    $dataUpdateMedia->save();
                }
                $preference = $request->preference;
                return redirect()->route('pagare', ['preference' => $preference]);
            }
        } else {
            return  response()->json('hola');
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

        $arr = [];
        if (is_array(json_decode($data['files_name'], true))) {
            foreach (json_decode($data['files_name'], true) as $key => $value) {
                $url = pathinfo($value);
                $extension = $url['extension'];
                $extension = strtok($extension, '?');
                $arr[] = $extension;
            }
            // return json_decode($data['files_name']);
            $data['files_name'] = json_decode($data['files_name'], true);
            return view('media.show', compact('data', 'arr'));
        } else {

            $url = pathinfo($data->files_name);
            $base = $url['filename'];
            $extension = $url['extension'];
            $extension = strtok($extension, '?');
            $data->files_name = Storage::disk('s3')->temporaryUrl($data->screen_id . '/' . date('Ymd', strtotime($data->date)) . '/' . $base . '.' . $extension, now()->addMinutes(1440));
            $data->save();
            $data['ext'] = $extension;
            return view('media.show', compact('data'));
        }
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


    public function approved($id)
    {
        Media::where('_id', '=', $id)->update(['approved' => 1]);
        return back();
    }

    public function notApproved($id)
    {
        Media::where('_id', '=', $id)->update(['approved' => 0]);
        return back();
    }
}
