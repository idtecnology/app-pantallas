@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Crear: </h2>
                <a class="btn btn-primary rounded-pill px-4" href="{{ route('users.index') }}"> Regresar</a>
            </div>
        </div>
    </div>


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    {!! Form::open(['route' => 'users.store', 'method' => 'POST']) !!}
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 mb-2">
            <div class="form-group">
                <strong>Nombres:</strong>
                {!! Form::text('name', null, ['placeholder' => 'Nombres', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 mb-2">
            <div class="form-group">
                <strong>Apellidos:</strong>
                {!! Form::text('last_name', null, ['placeholder' => 'Apellidos', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
            <div class="form-group">
                <strong>Correo:</strong>
                {!! Form::text('email', null, ['placeholder' => 'Correo', 'class' => 'form-control', 'autocomplete' => 'off']) !!}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 mb-2 ">
            <div class="form-group ">
                <strong>Contraseña:</strong>
                {!! Form::password('password', [
                    'placeholder' => 'Contraseña',
                    'class' => 'form-control',
                    'autocomplete' => 'off',
                ]) !!}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Confirmar contraseña:</strong>
                {!! Form::password('confirm-password', ['placeholder' => 'Confirmar contraseña', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 mb-2">
            <div class="form-group">
                <strong>Rol:</strong>
                {!! Form::select('roles[]', $roles, [], ['class' => 'form-select', 'multiple']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
            <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar</button>
        </div>
    </div>
    {!! Form::close() !!}


@endsection
