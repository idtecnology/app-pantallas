@extends('layouts.app')



@section('content')
    <div class="col-xs-12 overflow-scroll" width='100vw' height='98vh'>
        <table class="table table-bordered table-sm mb-0 text-center">
            <thead class="text-uppercase table-dark">
                <tr>
                    @can('admin-list')
                        <th>Cliente:</th>
                    @endcan

                    <th>Fecha:</th>
                    <th>Horario:</th>
                    <th>Duracion:</th>
                    <th>Estado:</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dato)
                    <tr class="align-middle">
                        @can('admin-list')
                            <td>{{ $dato->email }}</td>
                        @endcan
                        <td>{{ date('d-m-Y', strtotime($dato->date)) }}</td>
                        <td>{{ date('H:i', strtotime($dato->time)) }}</td>
                        <td>{{ $dato->duration }} segundos</td>
                        <td>
                            @switch($dato->approved)
                                @case(1)
                                    <span class="text-success">Aprobado</span>
                                @break

                                @case(1)
                                    <span class="text-success">Por aprobar</span>
                                @break

                                @default
                                    <span class="text-danger">No aprobado</span>
                            @endswitch
                        </td>

                        @can('admin-list')
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('sale.show', $dato->_id) }}" type="button"
                                        class="btn btn-primary btn-sm">Ver</a>
                                </div>
                            </td>
                        @else
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('sale.show', $dato->_id) }}" type="button"
                                        class="btn btn-primary btn-sm">Ver</a>
                                </div>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">
            {!! $data->render() !!}
        </div>
    </div>
@endsection
