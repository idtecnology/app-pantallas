@extends('layouts.app')



@section('content')
    <div class="col-12">
        <table class="table table-sm mb-b">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Duracion</th>
                    <th>fecha</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dato)
                    <tr class="align-middle">
                        <td>{{ $dato->name }}</td>
                        <td>{{ $dato->duration }}</td>
                        <td>{{ $dato->date }}</td>
                        <td>{{ $dato->type }}</td>
                        <td>{{ $dato->approved }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('media.show', $dato->_id) }}" type="button"
                                    class="btn btn-primary btn-sm">Ver</a>
                                <a href="{{ route('media.edit', $dato->_id) }}" type="button"
                                    class="btn btn-warning btn-sm">Editar</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
