@extends('layouts.app')


@section('content')
    <div id="datos" class="p-3">
        <span class="fs-3 fw-bold">
            Tus datos
        </span>
        <div class="mt-4 d-flex flex-column">

            <span class="fs-5 border border-1 shadow-sm mb-3 p-3">
                Nombre: {{ $user->name }}
            </span>
            <span class="fs-5 border border-1 shadow-sm mb-3  p-3">
                Apellido: {{ $user->last_name }}
            </span>
            <span class="fs-5 border border-1 shadow-sm mb-3  p-3">
                Correo: {{ $user->email }}
            </span>



            @if ($user->birth == null || $user->phone == null)
                <div class="p-2">
                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
                    <div class="row border border-1 shadow-sm mb-3 p-3">
                        <div class="col-4 mb-0 align-middle">
                            <label class="fs-5">Fecha de nacimiento:</label>
                        </div>
                        <div class="col-8">
                            <input type="date" name="birth" class="form-control">
                        </div>
                    </div>
                    <div class="row border border-1 shadow-sm mb-3 p-3">
                        <div class="col-4 mb-0 align-middle">
                            <label class="fs-5">Telefono:</label>
                        </div>
                        <div class="col-8">

                            {!! Form::text('phone', null, ['placeholder' => 'Telefono', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button class="btn btn-primary px-4 py-2 rounded-pill">Actualizar</button>
                    </div>
                </div>

                {!! Form::close() !!}
            @else
                <span class="fs-5 border border-1 shadow-sm mb-3  p-3">

                    Fecha de nacimiento: {{ date('d/m/Y', strtotime($user->birth)) }}
                </span>
                <span class="fs-5 border border-1 shadow-sm mb-3 p-3">
                    Telefono: {{ $user->phone }}
                </span>
            @endif


        </div>
    </div>
@endsection
