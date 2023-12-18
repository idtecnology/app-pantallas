@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Usuarios</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('clients.create') }}"> Nuevo usuario</a>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered table-sm mb-0 mt-3">
        <thead class="text-uppercase table-dark">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th width="280px">Acciones</th>
            </tr>
        </thead>

        @foreach ($data as $key => $user)
            <tr class="align-middle">
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $user->name }} {{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">
                    @if (!empty($user->getRoleNames()))
                        @foreach ($user->getRoleNames() as $v)
                            <span class="badge bg-success">{{ $v }}</span>
                        @endforeach
                    @endif
                </td>
                <td class="text-center">
                    <a class="btn btn-info" href="{{ route('clients.show', $user->id) }}"><span
                            style="vertical-align: middle; color:white;" class="material-symbols-outlined">
                            search
                        </span></a>
                    <a class="btn btn-warning" href="{{ route('clients.edit', $user->id) }}"><span
                            class="material-symbols-outlined" style="vertical-align: middle;">
                            edit
                        </span></a>
                </td>
            </tr>
        @endforeach
    </table>


    {!! $data->render() !!}
@endsection
