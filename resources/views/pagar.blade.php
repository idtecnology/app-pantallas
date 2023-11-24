@extends('layouts.app')
@section('content')

    <body onload="document.querySelector('.mercadopago-button').click()">
        <div class="d-none">
            <div class="cho-container"></div>
        </div>
    </body>
@endsection
@section('js')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        // window.onload = function() {
        //     ;
        // };


        const mp = new MercadoPago('{{ env('MP_CLIENT') }}', {
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
