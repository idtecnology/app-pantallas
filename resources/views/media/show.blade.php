@extends('layouts.app')

{{-- @dd($data) --}}

@section('content')
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

                                        @case(2)
                                            <span class="text-success">Por Aprobar</span>
                                        @break

                                        @default
                                            <span class="text-danger">No aprobado</span>
                                    @endswitch
                                </td>
                            </tr>
                            @can('admin-list')
                                @if ($data->date >= date('Y-m-d'))
                                    @if ($data->approved == 2)
                                        <tr class="text-center">
                                            <td>
                                                <a data-bs-toggle="modal" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop" type="button"
                                                    class="btn btn-success btn-sm">Aprobar</a>
                                            </td>
                                            <td>
                                                <a data-bs-toggle="modal" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop2" type="button"
                                                    class="btn btn-danger btn-sm">Desaprobar</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endcan
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @foreach ($data->files_name as $k => $valor)
        @if (in_array($arr[$k], config('ext_aviable.EXTENSIONES_PERMITIDAS_VIDEO')))
            <div class="col-12 col-md-4  mt-4 text-center">
                <video class="img-fluid" controls src="{{ $valor['file_name'] }}">
                    {{-- <source src="{{ $valor['file_name'] }}" type="video/{{ $arr[$k] }}" /> --}}
                    Your browser does not support the video tag.
                </video>
            </div>
        @else
            <div class="text-center">
                <img width="600px" height="400px" src="{{ $valor['file_name'] }}" alt=""
                    class="img-thumbnail img-fluid">
            </div>
        @endif
    @endforeach




    <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Aprobar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Esta seguro que desea aprobar
                </div>
                <div class="modal-footer">
                    <a href="{{ route('approved', $data->_id) }}" type="button" class="btn btn-success">Aprobar</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Desprobar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Esta seguro que desea desaprobar
                </div>
                <div class="modal-footer">
                    <a href="{{ route('notapproved', $data->_id) }}" type="button" class="btn btn-danger">Desprobar</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
