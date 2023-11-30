@extends('layouts.app')
@section('content')
    <div id="spinner"></div>
    <div class="d-none">
        <div class="cho-container"></div>
    </div>
@endsection
@section('js')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const mp = new MercadoPago('{{ env('TEST_MP_CLIENT') }}', {
            locale: 'es-AR'
        })

        mp.checkout({
            preference: {
                id: '{{ $preference }}'

            },
            render: {
                label: 'Pagar',
                container: '.cho-container'
            }
        })

        window.onload = function() {
            setTimeout(function() {
                spinner.setAttribute('hidden', '');
                document.querySelector('.mercadopago-button').click()
            }, 3000);
        };
    </script>
@endsection
