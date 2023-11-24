@extends('layouts.app')

@section('content')
    <div class="col-12">
        <table class="table table-bordered table-sm mb-0 text-center">
            <thead class="text-uppercase table-dark">
                <tr>
                    <th>Cliente:</th>
                    <th>Email:</th>
                    <th>Fecha:</th>
                    <th>Estado:</th>
                    <th>ID PAGO:</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dato)
                    <tr class="align-middle">
                        <td>{{ $dato->name }} {{ $dato->Danny }}</td>
                        <td>{{ $dato->email }}</td>
                        <td>{{ date('d-m-Y', strtotime($dato->updated_at)) }}</td>
                        <td>{{ $dato->status }}</td>
                        <td><a href="{{ route('pagos.show', $dato->_id) }}">{{ $dato->payment_id }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
