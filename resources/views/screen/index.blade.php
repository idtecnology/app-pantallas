@extends('layouts.app')

@section('content')
    <div class="col-12">
        <table class="table table-sm mb-b">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Ubicacion</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dato)
                    <tr class="align-middle">
                        <td>{{ $dato->name }}</td>
                        <td>{{ $dato->location }}</td>
                        <td>AR$ {{ number_format($dato->price, 2, ',', '.') }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('screen.show', $dato->_id) }}" type="button"
                                    class="btn btn-primary btn-sm">ver</a>
                                <a href="{{ route('screen.show', $dato->_id) }}" type="button"
                                    class="btn btn-warning btn-sm">Editar</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
