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
                        <td>{{ $dato->duration }} segundos</td>
                        <td>{{ date('d-m-Y', strtotime($dato->date)) }}</td>
                        <td>{{ $dato->type == 1 ? 'Video' : 'Slideshow' }}</td>
                        <td>{{ $dato->approved == 1 ? 'Aprovado' : 'Sin aprobar' }}</td>

                        @can('admin-list')
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('sale.show', $dato->_id) }}" type="button"
                                        class="btn btn-primary btn-sm">Ver</a>
                                    <a href="" type="button" class="btn btn-success btn-sm">Aprobar</a>
                                </div>
                            </td>
                        @else
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('sale.show', $dato->_id) }}" type="button"
                                        class="btn btn-primary btn-sm">Ver</a>
                                    <a href="{{ route('sale.edit', $dato->_id) }}" type="button"
                                        class="btn btn-warning btn-sm">Editar</a>
                                    <a href="{{ route('sale.programar', $dato->_id) }}" type="button"
                                        class="btn btn-success btn-sm">Programar</a>
                                </div>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
