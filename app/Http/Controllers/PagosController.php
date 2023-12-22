<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Pagos;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;

class PagosController extends Controller
{



    public function index()
    {

        $data_gen = [
            'prev_url' => "/home",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];

        $data = Pagos::select('pagos.*', 'users.name', 'users.last_name', 'users.email', 'media.reproducido')
            ->join('media', 'media.preference_id', '=', 'pagos.preference_id')
            ->join('users', 'users.id', '=', 'pagos.client_id')->get();

        return view('pagos.index', compact('data', 'data_gen'));
    }

    public function show($id)
    {
        $data_gen = [
            'prev_url' => "/pagos",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];

        $datos = Pagos::find($id);



        $client = new Client();

        $url = 'https://api.mercadopago.com/v1/payments/' . $datos->payment_id;
        $token = env('MP_SECRET');
        $contentType = 'application/json';

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => $contentType,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);



            return view('pagos.show', compact('data', 'statusCode', 'data_gen'));
        } catch (\Exception $e) {
            // Manejar errores
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function crearPago($preference)
    {
        return view('pagar', compact('preference'));
    }


    public function pendiente(Request $request)
    {
        return $request;
    }

    public function failure(Request $request)
    {
        $data_gen = [
            'prev_url' => "/",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];
        // return $request;
        $media_data = Media::where('preference_id', '=', $request->preference_id)->get()[0];

        if (!empty($media_data)) {
            $pago = new Pagos();
            $pago->media_id = $media_data->_id;
            $pago->client_id = $media_data->client_id;
            $pago->collection_id = $request->collection_id;
            $pago->collection_status = $request->collection_status;
            $pago->payment_id = $request->payment_id;
            $pago->status = $request->status;
            $pago->payment_type = $request->payment_type;
            $pago->preference_id = $request->preference_id;
            $pago->merchant_order_id = $request->merchant_order_id;
            $pago->save();
            $media = Media::find($media_data->_id);
            $media->isPaid = 0;
            $media->isActive = 0;
            $media->save();
            return view('pagos.failure', compact('data_gen'));
        }
    }



    public function success(Request $request)
    {
        $data_gen = [
            'prev_url' => "/",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];

        $media_data = Media::where('preference_id', '=', $request->preference_id)->get()[0];

        if (!empty($media_data)) {
            $pago = new Pagos();
            $pago->media_id = $media_data->_id;
            $pago->client_id = $media_data->client_id;
            $pago->collection_id = $request->collection_id;
            $pago->collection_status = $request->collection_status;
            $pago->payment_id = $request->payment_id;
            $pago->status = $request->status;
            $pago->payment_type = $request->payment_type;
            $pago->preference_id = $request->preference_id;
            $pago->merchant_order_id = $request->merchant_order_id;
            $pago->save();
            $media = Media::find($media_data->_id);
            $media->isPaid = 1;
            $media->isActive = 1;
            $media->save();



            //Enviamos mail aprobado.

            $client = User::find($media_data->client_id);
            $screen = Screen::find($media_data->screen_id);

            $to_name = $client->name . ' ' . $client->last_name;
            $to_email = $client->email;
            $data = [
                'screen_name' => $screen->nombre,
                'screen_location' => $screen->direccion,
                'media_time' => $media->time,
                'media_date' => $media->date,
                'media_duration' => $media->duration,
            ];


            Mail::send('email.aprobado', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                    ->subject('Recibimos tu pago correctamente');
                $message->from('no-responder@adsupp.com', 'AdsUpp');
            });

            return view('pagos.success', compact('data_gen'));
        }
    }


    public static function paymentMethods()
    {

        return [
            'excluded_payment_methods' => [['id' => 'argencard'], ['id' => 'cabal'], ['id' => 'cmr'], ['id' => 'cencosud'], ['id' => 'cordobesa'], ['id' => 'naranja'], ['id' => 'tarshop'], ['id' => 'debcabal']],
            'excluded_payment_types' => [['id' => 'ticket']],
            'installments' => 1,
        ];
    }


    public static function backUrls()
    {

        return
            [
                'success' => route('success'),
                'failure' => route('failure'),
                'pending' => route('pendiente'),
            ];
    }
}
