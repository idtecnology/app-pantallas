@extends('layouts.app')
@section('content')
    <div class="col-12">
        {!! Form::open(['route' => 'sale.store', 'method' => 'POST']) !!}
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

            <div class="col-8 mb-3">
                <div class="form-group">
                    <strong>Hora:</strong>
                    <input type="time" max='23:00' min="7:00" step="0" class="form-control">
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
