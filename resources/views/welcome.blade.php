@extends('layouts.app')

@section('content')
    <h1>Elejí tu pantalla</h1>
    <p class="bg-primary p-2 rounded-end-4 text-white w-75">Nunca fue tan facil publicar</p>

    <div class="row">
        <x-card-pantalla title='Pinamar' address='una direccion' />
        <x-card-pantalla />
        <x-card-pantalla />
        <x-card-pantalla />
        <x-card-pantalla />
    </div>
@endsection
