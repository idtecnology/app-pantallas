@extends('layouts.app')

{{-- @dd($data) --}}

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between mb-3">
                        <h3 class="">Aprovar</h3>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-success btn-sm">Aprovar</button>
                            <button type="button" class="btn btn-danger btn-sm">Desaprovar</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm mb-0 table-bordered">

                            <tr>
                                <td>Nombre</td>
                                <td colspan="5">{{ $data->name }}</td>
                            </tr>
                            <tr>
                                <td>fecha</td>
                                <td>{{ date('d-m-Y', strtotime($data->date)) }}</td>
                                <td>Tramo</td>
                                <td>{{ date('H:i', strtotime($data->time)) }}</td>
                                <td>Duracion</td>
                                <td>{{ $data->duration }} segundos</td>
                            </tr>
                            <tr>
                                <td>Tipo</td>
                                <td>
                                    @switch($data->type)
                                        @case(2)
                                            Slideshow
                                        @break

                                        @default
                                            Video
                                    @endswitch
                                </td>
                                <td>
                                    Aprovado
                                </td>
                                <td colspan="3">
                                    @switch($data->approved)
                                        @case(1)
                                            Aprovado
                                        @break

                                        @default
                                            Por aprovar
                                    @endswitch
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <span>Ver</span>
                </div>
            </div>
        </div>


    </div>
@endsection
