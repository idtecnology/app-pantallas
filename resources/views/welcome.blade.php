@extends('layouts.app')

@section('content')
    <h1 class="fw-bold mt-4">Elejí tu pantalla</h1>
    <p class="bg-primary p-2 rounded-end-4 text-white w-75">¡Publicar nunca fue tan facil!</p>

    <div class="row px-0 m-auto">
        @foreach ($screens as $screen)
            <x-card-pantalla img='{{ $screen->imagen }}' title='{{ $screen->nombre }}' address='{{ $screen->direccion }}'
                idscreen='{{ $screen->_id }}' />
        @endforeach


    </div>
@endsection
