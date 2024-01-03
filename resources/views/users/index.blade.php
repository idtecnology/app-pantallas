@extends('layouts.app')


@section('content')
    <div class="col-12 ">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Usuarios: </h2>
            <a class="btn btn-primary rounded-pill px-4" href="{{ route('users.create') }}"> Nuevo usuario</a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="col-12 mt-3">
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        </div>
    @endif

    <div class="col-12 overflow-auto">
        <table class="table table-bordered table-sm mb-0 mt-3 ">
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
                        <a class="btn btn-info" href="{{ route('users.show', $user->id) }}"><span
                                style="vertical-align: middle; color:white;" class="material-symbols-outlined">
                                search
                            </span></a>
                        <a class="btn btn-warning" href="{{ route('users.edit', $user->id) }}"><span
                                class="material-symbols-outlined" style="vertical-align: middle;">
                                edit
                            </span></a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="col-12 text-center">
        {!! $data->render() !!}
    </div>
@endsection
