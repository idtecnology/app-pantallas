@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Usuario:</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-dark" href="{{ route('users.index') }}"> Regresar</a>
            </div>
        </div>
    </div>


    <div class="row">
        <table class="table table-bordered table-sm mb-0 mt-3">
            <tr>
                <td>Nombre</td>
                <td>{{ $user->name }} {{ $user->last_name }}</td>
            </tr>
            <tr>
                <td>Correo</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>Rol</td>
                <td>
                    @if (!empty($user->getRoleNames()))
                        @foreach ($user->getRoleNames() as $v)
                            <label class="">{{ $v }}</label>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td>Fecha de nacimiento</td>
                <td>{{ $user->birth }}</td>
            </tr>

            <tr>
                <td>Telefono</td>
                <td>{{ $user->phone }}</td>
            </tr>

            <tr>
                <td>Porcentaje de descuento</td>
                <td>{{ $user->discounts }}%</td>
            </tr>
        </table>



    </div>
@endsection
