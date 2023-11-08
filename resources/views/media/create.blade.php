@extends('layouts.app')
@section('content')
    <div class="col-12">
        {!! Form::open(['route' => 'sale.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-8 mb-3">
                <div class="form-group">
                    <strong>Nombre:</strong>
                    {!! Form::text('name', null, ['placeholder' => 'Nombre', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Tipo:</strong>
                    <div class="form-check">
                        {{ Form::radio('type', '1', false, ['class' => 'form-check-input']) }}
                        <label class="form-check-label" for="flexRadioDefault1">
                            Video
                        </label>
                    </div>
                    <div class="form-check">
                        {{ Form::radio('type', '2', false, ['class' => 'form-check-input']) }}
                        <label class="form-check-label" for="flexRadioDefault1">
                            Slideshow
                        </label>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Punto de venta:</strong>
                    {!! Form::select('screen_id', $pos, ['placeholder' => 'Seleccione', 'class' => 'form-select']) !!}
                </div>
            </div>
            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Hora:</strong>
                    <input type="date" min='today' name='date' class="form-control">
                </div>
            </div>

            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Hora:</strong>
                    <input type="time" required max='23:00' min='07:00' name='time' class="form-control">
                </div>
            </div>
            <div class="col-3 mb-3">
                <div class="form-group">
                    <strong>Duracion en segundos:</strong>
                    <input type="number" max='60' step="15" min='0' name='duration' class="form-control">
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="form-group">
                    <strong>Ficheros:</strong>
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
