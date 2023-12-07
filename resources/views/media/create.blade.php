@extends('layouts.app')
@section('content')
    <div class="col-12">
        {!! Form::open(['route' => 'sale.store-massive', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="col-8 mb-3">
                    <div class="form-group">
                        <strong>Nombre campaña:</strong>
                        {!! Form::text('name', null, ['placeholder' => 'Nombre', 'class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <strong>Punto de venta:</strong>
                    <select class='form-select' name="screen_id" id="screen_id">
                        @foreach ($pos as $p)
                            <option value="{{ $p->_id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Fecha inicio:</strong>
                    <input type="date" min='{{ date('Y-m-d') }}' name='fecha_inicio' class="form-control">
                </div>
            </div>
            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Fecha fin:</strong>
                    <input type="date" min='{{ date('Y-m-d') }}' name='fecha_fin' class="form-control">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Hora inicio:</strong>
                    <input type="time" step="600" name='hora_inicio' class="form-control">
                </div>
            </div>
            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Hora fin:</strong>
                    <input type="time" step="600" name='hora_fin' class="form-control">
                </div>
            </div>


            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Cantidad por hora:</strong>
                    <input type="text" class="form-control" name="cant" id="cant">
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="form-group">
                    <strong>Multimedia:</strong>
                    <input type="file" name='files[]' multiple class="form-control">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
