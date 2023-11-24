@extends('layouts.app')

{{-- @dd($data) --}}

@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-between mb-3 align-items-center">
                            <a class="btn btn-sm btn-dark" href="{{ route('sale.index') }}">Regresar</a>
                            @can('admin-list')
                                <h3 class="">Aprobar</h3>
                            @endcan
                        </div>
                        <div class="col-12">
                            <table class="table table-sm mb-0 table-bordered">
                                <tr>
                                    <td scope='col' class="text-uppercase table-dark">Fecha:</td>
                                    <td>{{ date('d-m-Y', strtotime($data->date)) }}</td>
                                </tr>
                                <tr>
                                    <td scope='col' class="text-uppercase table-dark">Horario:</td>
                                    <td>{{ date('H:i', strtotime($data->time)) }}</td>
                                </tr>
                                <tr>
                                    <td scope='col' class="text-uppercase table-dark">Duracion:</td>
                                    <td>{{ $data->duration }} segundos</td>
                                </tr>
                                <tr>
                                    <td scope='col' class="text-uppercase table-dark">
                                        Estado:
                                    </td>
                                    <td>
                                        @switch($data->approved)
                                            @case(1)
                                                <span class="text-success">Aprobado</span>
                                            @break

                                            @default
                                                <span class="text-danger">Por aprobar</span>
                                        @endswitch
                                    </td>
                                </tr>
                                @can('admin-list')
                                    <tr class="text-center">
                                        <td>
                                            <a href="{{ route('approved', $data->_id) }}" type="button"
                                                class="btn btn-success btn-sm">Aprobar</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('notapproved', $data->_id) }}" type="button"
                                                class="btn btn-danger btn-sm">Desaprobar</a>
                                        </td>
                                    </tr>
                                @endcan
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-8 col-xs-12">
            @if (isset($arr))
                @foreach ($data->files_name as $k => $valor)
                    @if ($arr[$k] === 'mp4')
                        <div class="col-3"><video width="600px" height="400px" controls>
                                <source src="{{ $valor }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video></div>
                    @else
                        <div class=" text-center">
                            <img width="600px" height="400px" src="{{ $valor }}" alt=""
                                class="img-thumbnail  img-fluid">
                        </div>
                    @endif
                @endforeach
            @else
                @if ($data->ext === 'mp4')
                    <div class="col-3"><video width="600px" height="400px" controls>
                            <source src="{{ $data->files_name }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video></div>
                @else
                    <div class=" text-center">
                        <img width="600px" height="400px" src="{{ $data->files_name }}" alt=""
                            class="img-thumbnail img-fluid">
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
