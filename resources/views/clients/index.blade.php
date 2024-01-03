@extends('layouts.app')


@section('content')
    <div class="col-12 mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Clientes</h2>
            <a class="btn btn-primary rounded-pill" href="{{ route('clients.create') }}">
                Nuevo
                usuario</a>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="col-12 col-12 mt-3">
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        </div>
    @endif
    <div class="col-12 overflow-auto">

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


                        <a class="btn btn-danger" onclick="deletClient({{ $user->id }});">
                            <span class="material-symbols-outlined" style="vertical-align: middle;">
                                delete
                            </span></a>


                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="col-12 mt-3">
        {!! $data->render() !!}
    </div>
@endsection


@section('js')
    <script>
        function deletClient(id) {
            const url2 = `/mantenice/clients/eliminar`;
            Swal.fire({
                title: "Estas seguro de eliminar al usuario? ",
                text: "Estos cambios son irreversibles",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, deseo Eliminar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url2, {
                            method: 'POST',
                            body: JSON.stringify({
                                id: id
                            }),
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire({
                                title: "Eliminado!",
                                text: "El usuario ha sido eliminado con exito",
                                icon: "success"
                            });
                            window.location.href = '/mantenice/clients';
                        })
                        .catch(error => {
                            // Aquí manejas el error
                            console.error('Error al intentar eliminar:', error);
                        });
                }
            });
        }
    </script>
@endsection
