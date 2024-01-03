@extends('layouts.app')
@section('content')
    <div class="d-none">
        <div class="cho-container"></div>
    </div>
@endsection
@section('js')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const mp = new MercadoPago('{{ env('MP_CLIENT') }}', {
            locale: 'es-AR'
        })

        Swal.fire({
            title: 'Procesando datos!',
            html: 'Estamos generando la orden de compra, seras redirigido en un momento.',
            allowOutsideClick: false,
            showLoaderOnConfirm: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });

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
                document.querySelector('.mercadopago-button').click()
            }, 3000);
        };
    </script>
@endsection
