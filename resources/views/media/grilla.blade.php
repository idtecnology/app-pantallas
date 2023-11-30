@extends('layouts.app')



@section('content')
    <div class="col-12">
        <table class="table table-bordered table-sm mb-b">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Duracion</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Visto</th>
                    <th>Activar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dato)
                    <tr class="align-middle">
                        <td>{{ $dato->email }}</td>
                        <td>{{ $dato->duration }} segundos</td>
                        <td>{{ date('d-m-Y', strtotime($dato->date)) }}</td>
                        <td>{{ date('H:i', strtotime($dato->time)) }}</td>
                        <td>{{ $dato->isActive == 1 ? 'Visto' : 'No Visto' }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input checked class="form-check-input" type="checkbox" role="switch"
                                    id="flexSwitchCheckDefault">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Activo</label>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
