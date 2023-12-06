<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Screen;
use App\Models\Tramo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

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
            $data = Media::where('client_id', '=', $user->id)->where('isPaid', '=', 1)->get();
        }
        return view('media.index', compact('data'));
    }

    public function create()
    {
        // $data = Tramo::where('tramos', '>=', date('H:i'))->where('fecha', '>=', $request->fecha)->where('fecha', '<=', date('Y-m-d'))->where('duracion', '>', 15)->get();
        $pos = Screen::all();
        return view('media.create', compact('pos'));
    }


    public function guardarData(Request $request)
    {
        $extensionesPermitidasVideo = ['mp4'];
        $extensionesPermitidas = ['jpeg', 'png', 'jpg'];
        $archivos = $request->file('archivos');
        $durationInSeconds = [];
        foreach ($archivos as $ll => $archivo) {
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/uploads/tmp', $nombreArchivo);
            $rutaLocal = storage_path('app/public/uploads/tmp/' . $nombreArchivo);

            if (in_array($archivo->getClientOriginalExtension(), $extensionesPermitidasVideo)) {
                $ffmpeg = FFMpeg::fromDisk('public')->open('/uploads/tmp/' . $nombreArchivo);
                $durationInSeconds[] = $ffmpeg->getDurationInSeconds();
            } else if (in_array($archivo->getClientOriginalExtension(), $extensionesPermitidas)) {
                $durationInSeconds[] = 2;
            } else {
                $rutaLocal = storage_path('app/public/uploads/tmp/' . $nombreArchivo);
                unlink($rutaLocal);
                return response()->json(['status' => 0, 'error' => 'hubo un error con la carga, puede que alguno de los formatos no sea permitido [' . $archivo->getClientOriginalExtension() . '], Formatos permitidos: png, jpg, jepg, mp4'], 404);
            }
        }


        $sumaDuracion = array_sum($durationInSeconds);

        if ($sumaDuracion > $request->tiempo) {
            $rutaLocal = storage_path('app/public/uploads/tmp/' . $nombreArchivo);
            unlink($rutaLocal);
            return response()->json(['status' => 0, 'error' => 'tiempo excedido'], 404);
        }


        $prices = [
            ['seconds' => 15, 'amount' => 10000],
            ['seconds' => 30, 'amount' => 20000],
            ['seconds' => 45, 'amount' => 30000],
            ['seconds' => 60, 'amount' => 40000],
            ['seconds' => 120, 'amount' => 80000],
        ];

        if (isset(auth()->user()->discounts) && auth()->user()->discounts > 0) {
            $descuento = auth()->user()->discounts;
            foreach ($prices as $key => $value) {
                $prices[$key]['amount'] = $value['amount'] * ($descuento / 100);
            }
        }

        $tiempito = $request->tiempo;

        $matchingPrices = array_filter($prices, function ($price) use ($tiempito) {
            return $price['seconds'] == $tiempito;
        });


        $price = reset($matchingPrices);


        SDK::setAccessToken(env('TEST_MP_SECRET'));



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


        $files_names = [];

        $archivos = $request->file('archivos');
        if ($request->media_id > 0) {
            //Actualizamos los que estan
            $media_update = Media::where('_id', '=', $request->media_id)->get()[0];

            if (is_object(json_decode($media_update->files_name, true)) || is_array(json_decode($media_update->files_name, true))) {


                $imgs_temp = json_decode($media_update->files_name, true);

                foreach ($imgs_temp as $img_tmp) {
                    $rutaLocal = storage_path('app/public/uploads/tmp/' . $img_tmp['file_name']);
                    unlink($rutaLocal);
                }
            } else {
                $rutaLocal = storage_path('app/public/uploads/tmp/' . $media_update->files_name);
                unlink($rutaLocal);
            }
            foreach ($archivos as $ll => $archivo) {
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $archivo->storeAs('public/uploads/tmp/', $nombreArchivo);
                $files_names[] = ['file_name'  => $nombreArchivo, 'duration' => $durationInSeconds[$ll]];
            }
            Media::where('_id', '=', $request->media_id)->update(['files_name' => json_encode($files_names)]);
            return response()->json(['mensaje' => 'éxito', 'preference_id' => $preference->id]);
        } else {
            //insertamos nuevos. 
            $media = new Media();
            foreach ($archivos as $l => $archivo) {
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $archivo->storeAs('public/uploads/tmp', $nombreArchivo);
                $files_names[] = ['file_name'  => $nombreArchivo, 'duration' => $durationInSeconds[$l]];
            }

            $media->files_name = json_encode($files_names);
            $media->screen_id = $request->screen_id;
            $media->duration = $request->tiempo;
            $media->client_id = $request->client_id;
            $media->save();



            $item = new Item();
            $item->id = $media->_id;
            $item->title = 'Producto';
            $item->description = 'Tiempo en pantalla';
            $item->quantity = 1;
            $item->unit_price = $price['amount'];
            $item->currency_id = 'ARS';

            $preference->items = [$item];
            $preference->save();



            $upd_me = Media::find($media->_id);
            $upd_me->preference_id = $preference->id;
            $upd_me->save();

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
                            $rutaLocal = storage_path('app/public/uploads/tmp/' . $img_tmp['file_name']);
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
                            // $files_names[] = $path;
                            $files_names[] = ['file_name'  => $path, 'duration' => $img_tmp['duration']];
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
                            $rutaLocal = storage_path('app/public/uploads/tmp/' . $img_tmp['file_name']);
                            $name_file = $request->screen_id . '/' . date('Ymd', strtotime($request->fecha)) . '/' . $img_tmp['file_name'];
                            $nameF = '/' . date('Ymd', strtotime($request->fecha)) . '/' . $img_tmp['file_name'];
                            $path = Storage::disk('s3')->put($name_file, file_get_contents($rutaLocal));
                            $path = Storage::disk('s3')->temporaryUrl($path . $nameF, now()->addMinutes(1440));
                            // $files_names[] = $path;
                            $files_names[] = ['file_name'  => $path, 'duration' => $img_tmp['duration']];
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
                    $dataUpdateMedia->files_name = json_encode($files_names);
                    $dataUpdateMedia->save();
                }
                $preference = $request->preference;
                return redirect()->route('pagare', ['preference' => $preference]);
            }
        } else {
            return  response()->json('hola');
        }
    }


    public function show($id)
    {
        $data = Media::find($id);

        $arr = [];
        if (is_array(json_decode($data['files_name'], true))) {
            foreach (json_decode($data['files_name'], true) as $key => $value) {
                $url = pathinfo($value['file_name']);
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

    public function grilla()
    {

        $data = Media::select('media.*', 'users.email')->where('approved', '=', 1)->where('isPaid', '=', 1)->join('users', 'users.id', '=', 'media.client_id')->get();

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


    public static function getDataMedia($id)
    {
        return Media::getDataMedia($id);
    }


    public function storeMassive(Request $request)
    {

        // return $request;





        //! Ya se que guarda. 

        $extensionesPermitidasVideo = ['mp4'];
        $extensionesPermitidas = ['jpeg', 'png', 'jpg'];
        $archivos = $request->file('files');
        $durationInSeconds = [];
        $rutasLocales = [];

        foreach ($archivos as $ll => $archivo) {
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/uploads/tmp', $nombreArchivo);
            $rutaLocal = storage_path('app/public/uploads/tmp/' . $nombreArchivo);

            //  $rutasLocales[] =;

            if (in_array($archivo->getClientOriginalExtension(), $extensionesPermitidasVideo)) {
                $ffmpeg = FFMpeg::fromDisk('public')->open('/uploads/tmp/' . $nombreArchivo);
                $durationInSeconds[] = $ffmpeg->getDurationInSeconds();
            }
            if (in_array($archivo->getClientOriginalExtension(), $extensionesPermitidas)) {
                $durationInSeconds[] = 2;
            }
        }

        $sumaDuracion = array_sum($durationInSeconds);

        $tramos = Tramo::where('fecha', '=', $request->fecha)
            ->where('screen_id', '=', $request->screen_id)
            ->where('duracion', '>=', $sumaDuracion)
            ->whereRaw("TIMEDIFF(CONCAT(fecha, ' ', tramos), NOW()) > '00:05:00'")
            ->orderByRaw('RAND()')
            ->limit($request->cant)
            ->get();


        foreach ($archivos as $l => $archivo) {
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/uploads/tmp', $nombreArchivo);
            $files_names[] = ['file_name'  => $nombreArchivo, 'duration' => $durationInSeconds[$l]];
        }

        // return $tramos;


        $ids = [];


        for ($i = 0; $i < $request->cant; $i++) {
            // Tramo::where('_id', '=', $tramos[$i]->_id)->update(['duracion' => $resto]);
            $media = new Media();
            $media->files_name = json_encode($files_names);
            $media->screen_id = $request->screen_id;
            $media->tramo_id = $tramos[$i]->tramo_id;
            $media->date = $request->fecha;
            $media->client_id = auth()->user()->id;
            $media->isPaid = 1;
            $media->isActive = 1;
            $media->time = $tramos[$i]->tramos;
            $media->save();

            $ids[] = $media->_id;
        }


        foreach ($ids as $id) {
            $dataUpdateMedia = Media::find($id);

            if (is_object(json_decode($dataUpdateMedia->files_name, true)) || is_array(json_decode($dataUpdateMedia->files_name, true))) {
                $imgs_temp = json_decode($dataUpdateMedia->files_name, true);
                $files_names2 = [];
                foreach ($imgs_temp as $img_tmp) {
                    $rutaLocal = storage_path('app/public/uploads/tmp/' . $img_tmp['file_name']);
                    $name_file = $request->screen_id . '/' . date('Ymd', strtotime($request->fecha)) . '/' . $img_tmp['file_name'];
                    $nameF = '/' . date('Ymd', strtotime($request->fecha)) . '/' . $img_tmp['file_name'];
                    $path = Storage::disk('s3')->put($name_file, file_get_contents($rutaLocal));
                    $path = Storage::disk('s3')->temporaryUrl($path . $nameF, now()->addMinutes(1440));
                    // $files_names[] = $path;
                    $files_names2[] = ['file_name'  => $path, 'duration' => $img_tmp['duration']];
                }

                $dataUpdateMedia->files_name = json_encode($files_names2);
                $dataUpdateMedia->save();
            }
        }
        unlink($rutaLocal);


        return redirect()->route('sale.index');
    }
}
