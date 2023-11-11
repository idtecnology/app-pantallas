@extends('layouts.app')

@section('content')
    <h1>Elegi tu pantalla</h1>
    <h3 class="bg-primary w-25 p-2 rounded-end-4 text-white">Nunca fue tan facil publicar</h3>

    <div class="row">
        <x-card-pantalla title='Pinamar' address='una direccion' />
        <x-card-pantalla />
        <x-card-pantalla />
        <x-card-pantalla />
        <x-card-pantalla />
    </div>
@endsection
