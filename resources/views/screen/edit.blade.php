@extends('layouts.app')
@section('content')
    <div class="col-12">
        {!! Form::model($data, ['method' => 'PATCH', 'route' => ['screen.update', $data->_id]]) !!}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Nombre:</strong>
                    {!! Form::text('name', null, ['placeholder' => 'Nombre', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Ubicacion:</strong>
                    {!! Form::text('location', null, ['placeholder' => 'Ubicacion', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Precio:</strong>
                    {!! Form::number('price', null, ['placeholder' => 'Precio (Ej: 21.10)', 'class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
