@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit New User</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
            </div>
        </div>
    </div>


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
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
                {!! Form::text('email', null, ['placeholder' => 'Correo', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 mb-2 ">
            <div class="form-group ">
                <strong>Contraseña:</strong>
                {!! Form::password('password', ['placeholder' => 'Contraseña', 'class' => 'form-control']) !!}
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
                {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-select', 'multiple']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
            <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar</button>
        </div>
    </div>
    {!! Form::close() !!}


@endsection
