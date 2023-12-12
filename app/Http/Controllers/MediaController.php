<?php

namespace App\Http\Controllers;

use App\Mail\AprobadoMail;
use App\Models\Campania;
use App\Models\Media;
use App\Models\Screen;
use App\Models\Tramo;
use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            $data = Media::select('media.*', 'users.email')
                ->where('isPaid', '=', 1)
                ->whereNull('campania_id')
                ->join('users', 'users.id', '=', 'media.client_id')
                ->paginate(20);
        } else {
            $data = Media::where('client_id', '=', $user->id)->where('isPaid', '=', 1)->paginate(20);
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


    public function searchProgramation(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);


        $data = Media::select('media._id as media_id', 'media.client_id as media_client_id', 'media.reproducido as media_reproducido', 'media.time as media_time', 'media.date as media_date', 'media.duration as media_duration', 'media.files_name as media_files_name', 'media.isActive as media_isActive', 'users.email', 'campanias.name as campania_name', 'screens.nombre as screen_name')
            ->where('media.approved', '=', 1)
            ->where('media.date', '=', $request->fecha)
            ->where('media.screen_id', '=', $request->screen_id)
            ->join('users', 'users.id', '=', 'media.client_id')
            ->join('campanias', 'campanias._id', '=', 'media.campania_id', 'left outer')
            ->join('screens', 'screens._id', '=', 'media.screen_id')
            ->orderBy('media_time', 'ASC')
            ->orderBy('media_id', 'ASC')
            ->paginate($itemsPerPage);

        $arr1 = [];
        $arr2 = [];


        foreach ($data as $media) {
            $arr1[$media->media_time][] = $media;
            $arr2[] = $media->media_time;
        }

        $arr3 = array_values(array_unique($arr2));

        return response()->json(['data' => $data, 'arr3' => $arr3, 'arr1' => $arr1], 200);
    }

    public function grilla(Request $request)
    {

        $pos = Screen::all();
        return view('media.grilla', compact('pos'));
    }


    public function selectScreen($id)
    {
        $data = Media::select('media._id as media_id', 'media.client_id as media_client_id', 'media.reproducido as media_reproducido', 'media.time as media_time', 'media.date as media_date', 'media.duration as media_duration', 'media.files_name as media_files_name', 'media.isActive as media_isActive', 'users.email', 'campanias.name as campania_name', 'screens.nombre as screen_name')
            ->where('media.approved', '=', 1)
            ->where('media.date', '=', date('Y-m-d'))
            ->where('media.screen_id', '=', $id)
            ->join('users', 'users.id', '=', 'media.client_id')
            ->join('campanias', 'campanias._id', '=', 'media.campania_id', 'left outer')
            ->join('screens', 'screens._id', '=', 'media.screen_id')
            ->orderBy('media_time', 'ASC')
            ->orderBy('media_id', 'ASC')
            ->paginate(20);
        return $data;

        $arr1 = [];
        $arr2 = [];


        foreach ($data as $media) {
            $arr1[$media->media_time][] = $media;
            $arr2[] = $media->media_time;
        }

        $arr3 = array_values(array_unique($arr2));


        $pos = Screen::all();
    }


    public function disabledMedia($id)
    {
        $data = Media::find($id);
        if (!empty($data)) {
            if ($data->isActive == 1) {
                $data->isActive = 0;
                $data->save();
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Desactivado con exito', 'isActive' =>  $data->isActive], 200);
            } else {
                $data->isActive = 1;
                $data->save();
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Activado con exito', 'isActive' =>  $data->isActive], 200);
            }
        } else {
            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Media no encontrada'], 404);
        }
    }


    public function approved($id)
    {
        Media::where('_id', '=', $id)->update(['approved' => 1]);
        return back();
    }

    public function notApproved($id)
    {
        $media = Media::find($id);
        $client = User::find($media->client_id);

        $media->approved = 0;
        $media->save();

        $to_name = $client->name . ' ' . $client->last_name;
        $to_email = $client->email;


        $data = [];



        Mail::send('email.noaprobado', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Tu publicación no ha sido aprobada');
            $message->from('no-responder@adsupp.com', 'AdsUpp');
        });
        return back();
    }


    public static function getDataMedia($id)
    {
        return Media::getDataMedia($id);
    }


    public function storeMassive(Request $request)
    {

        // return $request;

        $campania = new Campania();


        $campania->name = $request->name;
        $campania->screen_id = $request->screen_id;
        $campania->fecha_inicio = $request->fecha_inicio;
        $campania->fecha_fin = $request->fecha_fin;
        $campania->hora_inicio = $request->hora_inicio;
        $campania->hora_fin = $request->hora_fin;
        $campania->cant = $request->cant;
        $campania->save();

        $campania_id = $campania->_id;

        $fechaInicio = new DateTime($request->fecha_inicio);
        $fechaFin = new DateTime($request->fecha_fin);
        $intervalo = $fechaInicio->diff($fechaFin);
        $diferenciaEnDias = $intervalo->days;
        $horaInicio = new DateTime($request->hora_inicio);
        $horaFin = new DateTime($request->hora_fin);
        $intervaloHoras = $horaInicio->diff($horaFin);
        $diferenciaEnHoras = $intervaloHoras->h;
        $fechaActual = $request->fecha_inicio;
        $extensionesPermitidasVideo = ['mp4', 'mov'];
        $extensionesPermitidas = ['jpeg', 'png', 'jpg', 'webp'];
        $archivos = $request->file('files');
        $durationInSeconds = [];
        $rutasLocales = [];

        foreach ($archivos as $ll => $archivo) {
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/uploads/tmp', $nombreArchivo);
            $rutaLocal = storage_path('app/public/uploads/tmp/' . $nombreArchivo);
            if (in_array($archivo->getClientOriginalExtension(), $extensionesPermitidasVideo)) {
                $ffmpeg = FFMpeg::fromDisk('public')->open('/uploads/tmp/' . $nombreArchivo);
                $durationInSeconds[] = $ffmpeg->getDurationInSeconds();
            }
            if (in_array($archivo->getClientOriginalExtension(), $extensionesPermitidas)) {
                $durationInSeconds[] = 2;
            }

            $files_names[] = ['file_name'  => $nombreArchivo, 'duration' => $durationInSeconds[$ll]];
            $rutasLocales[] = $rutaLocal;
        }


        $sumaDuracion = array_sum($durationInSeconds);

        $ids = [];


        for ($i = 0; $i <= $diferenciaEnDias; $i++) {
            $tramos = Tramo::select('tramos', 'tramo_id')->where('fecha', '=', $fechaActual)
                ->where('screen_id', '=', $request->screen_id)
                ->where('duracion', '>', $sumaDuracion)
                ->where('tramos', '>=', $request->hora_inicio)
                ->where('tramos', '<', $request->hora_fin)
                ->get();

            // return $tramos;

            if (!empty($tramos)) {
                $tramosPorFecha = [];
                $j = 0;
                $h = 0;

                for ($t = 0; $t < $diferenciaEnHoras; $t++) {
                    while ($j < $request->cant) {
                        for ($p = $h; $p < (6 + $h); $p++) {
                            if ($j == $request->cant) {
                                $j++;
                                break;
                            }
                            $tramosPorFecha[] = $tramos[$p]->tramos;
                            $media = new Media();
                            $media->files_name = json_encode($files_names);
                            $media->screen_id = $request->screen_id;
                            $media->tramo_id = $tramos[$p]->tramo_id;
                            $media->date = $fechaActual;
                            $media->client_id = auth()->user()->id;
                            $media->isPaid = 1;
                            $media->approved = 1;
                            $media->isActive = 1;
                            $media->time = $tramos[$p]->tramos;
                            $media->campania_id = $campania_id;
                            $media->save();
                            $ids[] = $media->_id;
                            $j++;
                        }
                    }
                    $h += 6;
                    $j = 0;
                }
            }
            if ($fechaActual == $request->fecha_inicio) {
                $fechaActual = $fechaInicio->add(new DateInterval('P1D'));
                $fechaActual = $fechaActual->format('Y-m-d');
            }
        }


        foreach ($ids as $id) {
            $data = Media::find($id);
            $files_names2 = [];
            foreach (json_decode($data->files_name, true) as $filename) {
                $rutaLocal3 = storage_path('app/public/uploads/tmp/' . $filename['file_name']);
                $name_file2 = $data->screen_id . '/' . date('Ymd', strtotime($data->date)) . '/' . $filename['file_name'];
                $nameF2 = '/' . date('Ymd', strtotime($data->date)) . '/' . $filename['file_name'];
                $path2 = Storage::disk('s3')->put($name_file2, file_get_contents($rutaLocal3));
                $path2 = Storage::disk('s3')->temporaryUrl($path2 . $nameF2, now()->addMinutes(1440));
                $files_names2[] = ['file_name'  => $path2, 'duration' => $filename['duration']];
            }
            Media::where('_id', '=', $id)->update(['files_name' => json_encode($files_names2)]);
            $x[$id][] = json_encode($files_names2);
        }
        foreach ($rutasLocales as $ruta2) {
            unlink($ruta2);
        }

        return redirect()->route('sale.index');
    }


    public function reproducido($id)
    {
        $media = Media::find($id);


        if (!empty($media)) {
            if ($media->reproducido !== 1) {
                $rep = Media::where('_id', '=', $id)->update(['reproducido' => 1]);
                if ($rep == 1) {
                    return response()->json(['status' => 'success', 'code' => 200, 'message' => 'actualizacion exitosa'], 200);
                } else {
                    return response()->json(['status' => 'error', 'code' => 400, 'message' => 'Ocurrio un error en la actualizacion'], 400);
                }
            } else {
                return response()->json(['status' => 'error', 'code' => 400, 'message' => 'La media ya se encuentra actualizada'], 400);
            }
        } else {
            return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Media no encontrada'], 404);
        }
    }
}
