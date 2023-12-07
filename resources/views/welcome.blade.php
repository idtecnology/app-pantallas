@extends('layouts.app')

@section('content')
    <h1 class="fw-bold">Elejí tu pantalla</h1>
    <p class="bg-primary p-2 rounded-end-4 text-white w-75">¡Nunca fue tan facil publicar!</p>

    <div class="row">
        @foreach ($screens as $screen)
            <x-card-pantalla img='{{ $screen->imagen }}' title='{{ $screen->nombre }}' address='{{ $screen->direccion }}'
                idscreen='{{ $screen->_id }}' />
        @endforeach


    </div>
@endsection
