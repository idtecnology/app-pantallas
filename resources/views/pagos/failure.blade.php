@extends('layouts.app')
@section('content')
    <div class="col-12 text-center">
        <span class="material-symbols-outlined text-primary" style="font-size: 300px !important;">
            error
        </span>
        <h3>Pago rechazado por fondos insuficientes</h3>
    </div>
@endsection

@section('js')
    <script>
        window.onload = function() {

            setTimeout(function() {

                window.location.href = '/';
            }, 30000);
        };
    </script>
@endsection
