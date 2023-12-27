@extends('layouts.app')

@section('content')
    <div class="col-12 overflow-auto">
        <table class="table table-bordered table-sm mb-0 text-center">
            <thead class="text-uppercase table-dark align-middle">
                <tr>
                    <th>ID PAGO:</th>
                    <th>Cliente:</th>
                    <th>Email:</th>
                    <th>Fecha:</th>
                    <th>Estado/pago:</th>
                    <th>Repro:</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dato)
                    <tr class="align-middle">
                        <td><a href="{{ route('pagos.show', $dato->_id) }}">{{ $dato->payment_id }}</a></td>
                        <td>{{ $dato->name }} {{ $dato->Danny }}</td>
                        <td>{{ $dato->email }}</td>
                        <td>{{ date('d-m-Y', strtotime($dato->updated_at)) }}</td>
                        <td>{{ $dato->status }}</td>
                        <td>{{ $dato->reproducido === 1 ? 'Si' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
