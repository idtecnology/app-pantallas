@extends('layouts.app')
@section('content')
    <div class="d-none">
        <div class="cho-container"></div>
    </div>
@endsection
@section('js')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        window.addEventListener('load', function() {
            // Simula un clic en el botón de Mercado Pago
            document.querySelector('.mercadopago-button').click();
        });
        const mp = new MercadoPago('TEST-c00d4341-14f8-4d93-ab17-ef2b8b1e9983', {
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
    </script>
@endsection
