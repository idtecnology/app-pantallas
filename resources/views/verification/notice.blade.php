@extends('layouts.app')

@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <div class="bg-light p-5 rounded">
                <h3>Verificacion</h3>

                @if (session('message'))
                    <div class="alert alert-success" role="alert">
                        Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                    </div>
                @endif

                Antes de continuar, consulte su correo electrónico para obtener un enlace de verificación. Si no recibió el
                correo electrónico,
                <form action="{{ route('verification.send') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="d-inline btn btn-link p-0">
                        haga clic aquí para solicitar otro
                    </button>.
                </form>
            </div>
        </div>
    </div>
@endsection
