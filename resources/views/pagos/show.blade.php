@extends('layouts.app')

@section('content')
    <div class="col-12">
        <table class="table table-sm table-bordered">
            <tr>
                <th colspan="6">Datos del cliente:</th>
            </tr>
            <tr>
                <td>Nombre:</td>
                <td>{{ $data['payer']['first_name'] }} {{ $data['payer']['last_name'] }}</td>
                <td>Identificacion:</td>
                <td colspan="3">{{ $data['payer']['identification']['type'] }}
                    {{ $data['payer']['identification']['number'] }}</td>
            </tr>
            <tr>
                <td>Telefono:</td>
                <td>{{ $data['payer']['phone']['area_code'] }} {{ $data['payer']['phone']['number'] }} </td>
                <td>Email:</td>
                <td colspan="3">{{ $data['payer']['email'] }}</td>
            </tr>
            <tr>
                <th colspan="6">Datos de la tarjeta</th>
            </tr>
            <tr>
                <td>Nombre de la tarjeta:</td>
                <td>{{ $data['card']['cardholder']['name'] }}</td>
                <td>Identificacion:</td>
                <td colspan="3">{{ $data['card']['cardholder']['identification']['type'] }}
                    {{ $data['card']['cardholder']['identification']['number'] }}</td>
            </tr>
            <tr>
                <th colspan="6">Datos de la orden:</th>
            </tr>
            <tr>
                <td>Orden:</td>
                <td>{{ $data['order']['id'] }}</td>
                <td>Tipo:</td>
                <td colspan="3">{{ $data['order']['type'] }}</td>
            </tr>
            <tr>
                <td>Estado:</td>
                <td>{{ $data['status'] }}</td>
                <td>Detalle:</td>
                <td colspan="3">{{ $data['status_detail'] }}</td>
            </tr>
            <tr>
                <td>Tipo de pago:</td>
                <td>{{ $data['payment_method']['type'] }}</td>
                <td>Monto:</td>
                <td>{{ $data['transaction_amount'] }}</td>
                <td>Impuestos:</td>
                <td>{{ $data['taxes_amount'] }}</td>
            </tr>

        </table>
    </div>
@endsection
