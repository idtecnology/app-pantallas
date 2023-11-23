<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Pagos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;

class PagosController extends Controller
{

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
        return $request;
    }



    public function success(Request $request)
    {


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

            return redirect()->route('sale.index');
        }
    }
}
