<?php

namespace App\Http\Controllers;

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
        // $this->middleware('verified');
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
                ->where('approved', '=', 2)
                ->whereNull('campania_id')
                ->join('users', 'users.id', '=', 'media.client_id')
                ->orderBy('_id', 'ASC')
                ->paginate(20);
        } else {
            $data = Media::where('client_id', '=', $user->id)->where('isPaid', '=', 1)->orderBy('_id', 'ASC')->paginate(20);
        }
        return view('media.index', compact('data'));
    }

    public function create()
    {
        $pos = Screen::all();
        return view('media.create', compact('pos'));
    }


    public function guardarData(Request $request)
    {
        $archivos = $request->file('archivos');

        $validatedMedia =  $this->validaFormatomultimediaClient($archivos, $request->tiempo);

        if ($validatedMedia['status'] != false) {
            $prices = config('price-list.PRICE_LIST');

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


            SDK::setAccessToken(env('MP_SECRET'));



            // Instanciamos la preferencia: 
            $preference = new Preference();

            $preference->payment_methods = PagosController::paymentMethods();
            $preference->back_urls = PagosController::backUrls();

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
                }
                Media::where('_id', '=', $request->media_id)->update(['files_name' => json_encode($validatedMedia['files_names'])]);
                return response()->json(['mensaje' => 'éxito']);
            } else {
                //insertamos nuevos. 
                $media = new Media();

                $media->files_name = json_encode($validatedMedia['files_names']);
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
        } else {
            return response()->json(['mensaje' => $validatedMedia['message'], 'status' => 0]);
        }
    }


    public function store(Request $request)
    {
        $tramo = Tramo::where('fecha', '=', $request->fecha)
            ->where('screen_id', '=', $request->screen_id)
            ->where('tramos', '=', $request->tramo_select)
            ->get();



        if ($request->media_id != '') {
            $dataUpdateMedia = Media::find($request->media_id);
            if ($tramo[0]->duracion > $request->duration) {
                $resto = $tramo[0]->duracion - $request->duration;
                Tramo::where('_id', '=', $tramo[0]->_id)->update(['duracion' => $resto]);
                $dataUpdateMedia->screen_id = $request->screen_id;
                $dataUpdateMedia->time = $request->tramo_select;
                $dataUpdateMedia->duration = $request->duration;
                $dataUpdateMedia->date = $request->fecha;
                $dataUpdateMedia->tramo_id = $tramo[0]->tramo_id;

                $files_names = [];
                if (is_object(json_decode($dataUpdateMedia->files_name, true)) || is_array(json_decode($dataUpdateMedia->files_name, true))) {
                    $imgs_temp = json_decode($dataUpdateMedia->files_name, true);
                    foreach ($imgs_temp as $img_tmp) {
                        $rutaLocal = storage_path('app/public/uploads/tmp/' . $img_tmp['file_name']);
                        $name_file = $request->screen_id . '/' . date('Ymd', strtotime($request->fecha)) . '/' . $img_tmp['file_name'];
                        $path = Storage::disk('s3')->put($name_file, file_get_contents($rutaLocal));
                        $path = Storage::disk('s3')->temporaryUrl($name_file, now()->addMinutes(1440));
                        $files_names[] = ['file_name'  => $path, 'duration' => $img_tmp['duration']];
                        unlink($rutaLocal);
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
        $extensionesPermitidasVideo = ['mp4', 'mov', 'avi'];
        $extensionesPermitidas = ['jpeg', 'png', 'jpg', 'webp'];
        $archivos = $request->file('files');
        $durationInSeconds = [];

        // Validamos los formatos de la multimedia.
        $validarArchivos = $this->validaFormatomultimedia($archivos);


        $fecha_anterior = $fechaActual;

        if ($validarArchivos != false) {
            $files_names = $validarArchivos['files_names'];
            $sumaDuracion = array_sum($durationInSeconds);
            $insert_media = [];
            $discount = [];
            for ($i = 0; $i <= $diferenciaEnDias; $i++) {
                if ($horaFin < $horaInicio) {
                    $tramos_1 = Tramo::select('tramos', 'tramo_id', '_id', 'fecha', 'duracion')
                        ->where('fecha', '=', $fechaActual)
                        ->where('screen_id', '=', $request->screen_id)
                        ->where('duracion', '>', $sumaDuracion)
                        ->where('tramos', '>=', $horaInicio->format('H:i'))
                        ->where('tramos', '<=', '23:50')
                        ->orderBy('_id', 'asc');

                    $fechaSiguiente = $fechaInicio->modify('+1 day');

                    $tramos = Tramo::select('tramos', 'tramo_id', '_id', 'fecha', 'duracion')
                        ->where('fecha', '=', $fechaSiguiente->format('Y-m-d'))
                        ->where('screen_id', '=', $request->screen_id)
                        ->where('duracion', '>', $sumaDuracion)
                        ->where('tramos', '>=', '00:00')
                        ->where('tramos', '<=', $horaFin->format('H:i'))
                        ->union($tramos_1)
                        ->orderBy('_id', 'asc')
                        ->get();
                } else {
                    $tramos = Tramo::select('tramos', 'tramo_id', '_id', 'fecha', 'duracion')
                        ->where('fecha', '=', $fechaActual)
                        ->where('screen_id', '=', $request->screen_id)
                        ->where('duracion', '>',  $sumaDuracion)
                        ->where('tramos', '>=', $request->hora_inicio)
                        ->where('tramos', '<', $request->hora_fin)
                        ->get();
                }

                $tramosCounter = count($tramos) / 6;
                if (!empty($tramos)) {
                    $j = 0;
                    $h = 0;
                    for ($t = 0; $t < (int)$tramosCounter; $t++) {
                        while ($j < $request->cant) {
                            for ($p = $h; $p < (6 + $h); $p++) {
                                if ($j == $request->cant) {
                                    $j++;
                                    break;
                                }

                                $discount[$tramos[$p]->_id]['ids'][] = $tramos[$p]->_id;
                                $discount[$tramos[$p]->_id]['duracion'] = $tramos[$p]->duracion;
                                $insert_media[$p][] = [
                                    'campania_id'  => $campania_id,
                                    'client_id' => auth()->user()->id,
                                    'tramo_id' => $tramos[$p]->tramo_id,
                                    'screen_id' => $request->screen_id,
                                    'time' => $tramos[$p]->tramos,
                                    'date' => $tramos[$p]->fecha,
                                    'duration' => 15,
                                    'approved' => 1,
                                    'files_name' => json_encode($validarArchivos['files_names'][0]),
                                    'approved' => 1,
                                    'isPaid' => 1,
                                    'isActive' => 1
                                ];
                                $j++;
                            }
                        }
                        $h += 6;
                        $j = 0;
                    }
                }


                if ($horaFin < $horaInicio) {
                    $fechaActual = $fechaSiguiente;
                    $fechaActual = $fechaActual->format('Y-m-d');
                } else {
                    $fechaActual = $fechaInicio->add(new DateInterval('P1D'));
                    $fechaActual = $fechaActual->format('Y-m-d');
                }
            }


            for ($gg = 0; $gg < count($insert_media); $gg++) {
                Media::insert($insert_media[$gg]);
            }

            $this->updateS3($validarArchivos['files_names'][0]['file_name'], $campania_id,  $request->screen_id, $request->fecha_inicio, $request->fecha_fin, $validarArchivos['files_names'][0]);

            $keys = array_keys($discount);
            for ($k = 0; $k < count($discount); $k++) {
                $calculoResto = $discount[$keys[$k]]['duracion'] - (count($discount[$keys[$k]]['ids']) * 15);
                Tramo::where('_id', '=', $keys[$k])->update(['duracion' => $calculoResto]);
            }
            $this->eliminaTemp($validarArchivos['rutasLocales']);
            return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Masividad cargada con exito'], 200);
        } else {
            return response()->json(['status' => 'error', 'code' => 200, 'message' => 'formato no valido'], 200);
        }
    }



    protected function validaFormatomultimedia($archivos)
    {
        foreach ($archivos as $ll => $archivo) {
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/uploads/tmp', $nombreArchivo);
            $rutaLocal = storage_path('app/public/uploads/tmp/' . $nombreArchivo);
            if (in_array($archivo->getClientOriginalExtension(), config('ext_aviable.EXTENSIONES_PERMITIDAS_VIDEO'))) {
                $ffmpeg = FFMpeg::fromDisk('public')->open('/uploads/tmp/' . $nombreArchivo);
                $durationInSeconds[] = $ffmpeg->getDurationInSeconds();
            } else if (in_array($archivo->getClientOriginalExtension(), config('ext_aviable.EXTENSIONES_PERMITIDAS_IMAGEN'))) {
                $durationInSeconds[] = 2;
            } else {
                unlink($rutaLocal);
                return false;
            }
            $files_names[] = ['file_name'  => $nombreArchivo, 'duration' => $durationInSeconds[$ll]];
            $rutasLocales[] = $rutaLocal;
        }

        return ['files_names' => $files_names, 'rutasLocales' => $rutasLocales];
    }


    protected function validaFormatomultimediaClient($archivos, $tiempo)
    {
        $durationInSeconds = [];
        $videos = [];
        $imagenes = [];

        foreach ($archivos as $ll => $archivo) {
            $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
            $archivo->storeAs('public/uploads/tmp', $nombreArchivo);
            $rutaLocal = storage_path('app/public/uploads/tmp/' . $nombreArchivo);


            if (in_array($archivo->getClientOriginalExtension(), config('ext_aviable.EXTENSIONES_PERMITIDAS_VIDEO'))) {
                $ffmpeg = FFMpeg::fromDisk('public')->open('/uploads/tmp/' . $nombreArchivo);
                // $durationInSeconds[] = $ffmpeg->getDurationInSeconds();
                $videos[] = ['file_name' => $nombreArchivo, 'duration' => $ffmpeg->getDurationInSeconds()];
            } else if (in_array($archivo->getClientOriginalExtension(), config('ext_aviable.EXTENSIONES_PERMITIDAS_IMAGEN'))) {
                // $durationInSeconds[] = 2;
                $imagenes[] = ['file_name' => $nombreArchivo, 'duration' => 0];
            } else {
                unlink($rutaLocal);
                return ['status' => false, 'message' => "El formato {$archivo->getClientOriginalExtension()} no es valido."];
            }
            $rutasLocales[] = $rutaLocal;
        }

        $videosDuration = array_sum(array_column($videos, 'duration'));

        $tiempoRestante = max(0, $tiempo - $videosDuration);

        $imagenesCount = count($imagenes);

        // return $imagenesCount;

        if ($tiempo == 15 && $imagenesCount > 7) {
            return response()->json(['status' => 0, 'error' => "El tiempo seleccionado es de: $tiempo segundos, y la multimedia subida tiene un tiempo de:  segundos, por favor verifique o seleccione un mayor tiempo."], 200);
        } elseif ($tiempo == 30 && $imagenesCount > 15) {
            return response()->json(['status' => 0, 'error' => "El tiempo seleccionado es de: $tiempo segundos, y la multimedia subida tiene un tiempo de:  segundos, por favor verifique o seleccione un mayor tiempo."], 200);
        } elseif ($tiempo == 45 && $imagenesCount > 22) {
            return response()->json(['status' => 0, 'error' => "El tiempo seleccionado es de: $tiempo segundos, y la multimedia subida tiene un tiempo de:  segundos, por favor verifique o seleccione un mayor tiempo."], 200);
        } elseif ($tiempo == 60 && $imagenesCount > 30) {
            return response()->json(['status' => 0, 'error' => "El tiempo seleccionado es de: $tiempo segundos, y la multimedia subida tiene un tiempo de:  segundos, por favor verifique o seleccione un mayor tiempo."], 200);
        } elseif ($tiempo == 120 && $imagenesCount > 60) {
            return response()->json(['status' => 0, 'error' => "El tiempo seleccionado es de: $tiempo segundos, y la multimedia subida tiene un tiempo de:  segundos, por favor verifique o seleccione un mayor tiempo."], 200);
        } else {

            $tiempoPorImagen = $imagenesCount > 0 ? floor($tiempoRestante / $imagenesCount) : 0;
            if ($imagenesCount > 0) {
                $diferencia = $tiempoRestante % $imagenesCount;
            } else {
                $diferencia = 0;
            }

            for ($i = 0; $i < $imagenesCount; $i++) {
                $imagenes[$i]['duration'] += $tiempoPorImagen;

                if ($diferencia > 0) {
                    $imagenes[$i]['duration']++;
                    $diferencia--;
                }
            }

            foreach ($imagenes as &$imagen) {
                $imagen['duration'] = $imagen['duration'];
            }

            $files_names = array_merge($videos, $imagenes);

            $sumaDuracion = array_sum(array_column($files_names, 'duration'));


            if ($sumaDuracion > $tiempo) {
                foreach ($rutasLocales as $rutas) {
                    unlink($rutas);
                }
                return ['status' => false, 'message' => "El tiempo seleccionado es de: $tiempo segundos, y la multimedia subida tiene un tiempo de:  $sumaDuracion segundos, por favor verifique o seleccione un mayor tiempo."];
            }
            return ['status' => true, 'files_names' => $files_names, 'rutasLocales' => $rutasLocales,];
        }
    }


    protected function eliminaTemp($rutasLocales)
    {
        foreach ($rutasLocales as $tmp) {
            unlink($tmp);
        }
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




    protected function updateS3(string $file_name, int $campania_id, int $screen_id, string $fecha_inicio, string $fecha_fin, $datas)
    {
        // $fecha = new DateTime($fechaActual);
        // $anterior = new DateTime($fecha_anterior);

        $fechaInicio = new DateTime($fecha_inicio);
        $fechaInicio_ruta = new DateTime($fecha_inicio);
        $fechaFin = new DateTime($fecha_fin);
        $intervalo = $fechaInicio->diff($fechaFin);
        $diferenciaEnDias = $intervalo->days;

        $rutaLocal3 = storage_path('app/public/uploads/tmp/' . $file_name);
        $nameF2 = $screen_id . '/' . $fechaInicio->format('Ymd') . '/' . $file_name;
        $path2 = Storage::disk('s3')->put($nameF2, file_get_contents($rutaLocal3));
        $rutaBase = $screen_id . '/' . $fechaInicio_ruta->format('Ymd') . '/' . $file_name;
        $path2 = Storage::disk('s3')->temporaryUrl($nameF2, now()->addMinutes(1440));
        $files_names2[] = ['file_name'  => $path2, 'duration' => $datas['duration']];

        Media::where('campania_id', '=', $campania_id)->where('date', '=', $fechaInicio->format('Y-m-d'))->update(['files_name' => json_encode($files_names2)]);

        for ($upd = 0; $upd <= $diferenciaEnDias; $upd++) {
            $files_names2 = [];
            // $fechaInicio = $fechaInicio->add(new DateInterval('P1D'));
            $fecha_siguiente = $fechaInicio->modify('+1 day');

            $nameF3 = $screen_id . '/' . $fecha_siguiente->format('Ymd') . '/' . $file_name;

            $path3 = Storage::disk('s3')->copy($rutaBase, $nameF3);

            $path3 = Storage::disk('s3')->temporaryUrl($nameF3, now()->addMinutes(1440));

            $files_names2[] = ['file_name'  => $path3, 'duration' => $datas['duration']];

            Media::where('campania_id', '=', $campania_id)->where('date', '=', $fecha_siguiente->format('Y-m-d'))->update(['files_name' => json_encode($files_names2)]);
        }
    }
}
