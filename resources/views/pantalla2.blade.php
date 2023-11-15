@extends('layouts.app')


@section('content')
    <div id="multimedia">
        <span class="fs-3 mb-3">Tu multimedia</span>
        <div class="row text-center mt-4" id="mediaaas">
            Seleccione la multimedia
        </div>
        <div class="w-100 mt-4">
            <a data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#staticBackdro2"
                class="btn btn-primary rounded-pill d-flex text-center align-middle">
                <span class="material-symbols-outlined">
                    edit
                </span>
                <span>Edita tu contenido</span></a>
        </div>
    </div>
    <div id="horario-text" class="mt-4 d-flex flex-column">
        <span class="fs-3 mb-3">Elegi tu horario</span>
        <span class="fs-5 mb-3">Tu publicación saldrá dentro de los 5 minutos siguientes al horario seleccionado.</span>
    </div>
    <div id="horario-select" class="mt-4 p-2">
        <div class="d-flex align-middle items-center">
            <span class="fs-3 fw-bold">Horario seleccionado:</span>
            <span class="fs-4 ms-3 align-middle" id="span_tramo">Hoy, 00:00 hs</span>
        </div>
        <div class="row text-center mt-3" id='fuera'>

        </div>
        <div class="mt-4 text-center px-2 py-2 shadow-sm border border-1">
            <a data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="">Ver horarios
                disponibles</a>
        </div>
    </div>

    <div id="resumen" class="mt-4">
        <span class="fw-bold fs-3">
            Resumen de tu compra
        </span>
        <div class="d-flex flex-column">
            <span>Tu publicacion sera de {{ $time }} segundos</span>
            <span id="fehca_visualizacion">Se visualizara el 27/10/2023 - 15:30 hs</span>
            <span>Total: @switch($time)
                    @case(30)
                        $20.000
                    @break

                    @case(45)
                        $30.000
                    @break

                    @case(60)
                        $40.000
                    @break

                    @case(120)
                        $80.000
                    @break

                    @default
                        $10.000
                @endswitch
            </span>
        </div>
        <div class="mt-2"><a class="btn btn-primary rounded-pill w-100 d-flex align-middle">
                <span class="material-symbols-outlined">
                    credit_card
                </span>
                <span class="ms-3">Ir a pagar</span>
            </a></div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Horarios disponibles</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="fecha">
                        <input onchange="buscarTramos(this.value, 1)" type="date" name="date" id="date"
                            class="form-control">
                        <input class="form-control" type="time" disabled id="tramo_select_2" name="tramo_select_2" />
                    </div>
                    <div id="diasDisponibles" class="mt-4 p-3 border border-1">
                        <div class="row" id="tramo"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdro2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cargar multimedia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="panel-alert"></div>
                    {!! Form::open([
                        'route' => 'sale.store',
                        'method' => 'POST',
                        'id' => 'file-upload',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <input type="hidden" id="tramo_select" name="tramo_select" />
                    <input type="hidden" id="screen_id" name="screen_id" value="{{ $id }}" />
                    <input type="hidden" id="duration" name="duration" value="{{ $time }}" />
                    <input type="hidden" id="fechasss" name="fecha" value="" />
                    <div class="row">
                        <div class="col-8 mb-3">
                            <div class="form-group">
                                <strong>Nombre:</strong>
                                {!! Form::text('name', null, ['placeholder' => 'Nombre', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="form-group">
                                <strong>Tipo:</strong>
                                <div class="form-check">
                                    {{ Form::radio('type', '1', false, ['class' => 'form-check-input']) }}
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Video
                                    </label>
                                </div>
                                <div class="form-check">
                                    {{ Form::radio('type', '2', false, ['class' => 'form-check-input']) }}
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Slideshow
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <strong>Ficheros:</strong>
                            <input type="file" name='files[]' multiple class="form-control">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>

                {!! Form::close() !!}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));

        buscarTramos("{{ date('Y-m-d') }}", 2)


        function buscarTramos(fecha, lugar) {

            var tramo = document.querySelector('#tramo')
            document.getElementById('fechasss').value = fecha

            fetch('/api/tramo', {
                    method: 'POST',
                    body: JSON.stringify({
                        fecha: fecha,
                        limit: lugar == 2 ? '5' : ''
                    }),
                    headers: {
                        'content-type': 'application/json',
                    }
                }).then(response => response.json())
                .then(data => {
                    if (lugar == 1) {
                        var divs = '';
                        if (data == '') {
                            divs += `Vacio, no hay tramos disponibles`
                        } else {
                            for (tramos in data) {
                                divs += `<div class="col-2 mb-2">
                                <a onclick="seleccionTramo(this, '1')" class="btn btn-primary px-4 py-1 rounded-pill">${data[tramos].tramos}</a>
                            </div>`
                            }
                            // divs += '<input type="hidden" id="tramo_select" name="tramo_select"/>'
                        }
                        tramo.innerHTML = divs;
                    } else {

                        for (tramos in data) {
                            divs += `<div class="col-2 mb-2">
                                <a onclick="seleccionTramo(this,'2')" class="btn btn-primary px-4 py-1 rounded-pill">${data[tramos].tramos}</a>
                            </div>`
                        }

                        console.log(fecha)


                        document.querySelector('#span_tramo').innerHTML = `Hoy, ${data[0].tramos} hs`
                        document.querySelector('#fehca_visualizacion').innerHTML =
                            `Se visualizara el ${fecha} - ${data[0].tramos} hs`
                        document.getElementById('tramo_select').value = data[0].tramos




                        document.querySelector('#fuera').innerHTML = ''
                        document.querySelector('#fuera').innerHTML = divs
                    }

                });
        }

        function seleccionTramo(tramo, lugar) {
            var textoDelEnlace = tramo.innerText || tramo.textContent;
            if (lugar == 1) {
                document.getElementById('tramo_select').value = textoDelEnlace
                document.getElementById('tramo_select_2').value = textoDelEnlace
                document.querySelector('#span_tramo').innerHTML = `Hoy, ${textoDelEnlace} hs`

            } else {
                document.querySelector('#span_tramo').innerHTML = `Hoy, ${textoDelEnlace} hs`
                document.getElementById('tramo_select').value = textoDelEnlace

            }


        }
        const csrfToken = "{{ csrf_token() }}";

        document.getElementById('file-upload').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('sale.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data.status === 1) {
                        document.getElementById('mediaaas').innerHTML =
                            `<div class="col-3"><img src="${data}" alt=""></div>`
                    } else {
                        document.getElementById('panel-alert').innerHTML = `
                        <div class="alert alert-danger" id='miAlerta' alert-dismissible fade show" role="alert">
                        ${data.message}
                    </div>`

                    }

                    setTimeout(function() {
                        document.getElementById('miAlerta').classList.remove('show');
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
