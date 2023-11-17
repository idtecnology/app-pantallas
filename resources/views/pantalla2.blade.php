@extends('layouts.app')


@section('content')
    <div id="horario-text" class="mb-2 d-flex flex-column">
        <span class="fs-3 mb-3">Elegí tu horario</span>
        <span class="fs-5 mb-3">Tu publicación saldrá dentro de los 5 minutos siguientes al horario seleccionado.</span>
    </div>
    <div id="horario-select" class="mt-2 p-2">
        <div class="d-flex align-middle items-center">
            <span class="fs-3 fw-bold">Horario seleccionado:</span>
            <span class="fs-4 ms-3 align-middle" id="span_tramo">Hoy, 00:00 hs</span>
        </div>

        <div class="mt-2 text-center px-2 py-2 shadow-sm border border-1">
            <a data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="">Ver horarios
                disponibles</a>
        </div>
    </div>

    <div id="multimedia">
        <span class="fs-3 mb-3">Tu multimedia</span>
        <div class="row text-center mt-4" id="mediaaas">
            Seleccione la multimedia
        </div>
        <div class="w-100 mt-4">
            <a data-bs-toggle="modal" id='selectTramo' data-bs-toggle="modal" data-bs-target="#staticBackdro2"
                class="btn btn-primary rounded-pill d-flex text-center align-middle disabled">
                <span class="material-symbols-outlined">
                    edit
                </span>
                <span>Edita tu contenido</span></a>
        </div>
    </div>

    <div id="resumen" class="mt-4">
        <span class="fw-bold fs-3">
            Resumen de tu compra
        </span>
        <div class="d-flex flex-column">
            <span>Tu publicacion sera de {{ $time }} segundos</span>
            <span id="fehca_visualizacion"></span>
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
        <div class="mt-2"><a id="pagar" href="{{ route('pagar') }}"
                class="btn btn-primary rounded-pill w-100 d-flex align-middle disabled">
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
                                {{-- <div class="form-check">
                                        {{ Form::radio('type', '2', false, ['class' => 'form-check-input']) }}
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Slideshow
                                        </label>
                                    </div> --}}
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <strong>Ficheros:</strong>
                            <input type="file" name='files[]' multiple class="form-control">
                        </div>
                    </div>

                    <div style="display: none;" class="progress" role="progressbar" aria-label="Default striped example"
                        aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-striped" id="progressBar" style="width: 0%"></div>
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
@endsection


@section('js')
    <script>
        buscarTramos("{{ date('Y-m-d') }}", 1)
        const csrfToken = "{{ csrf_token() }}";








        async function buscarTramos(fecha, lugar) {
            try {
                var tramo = document.querySelector('#tramo');
                document.getElementById('fechasss').value = fecha;
                const inputFecha = document.getElementById('date');

                const response = await fetch('/api/tramo', {
                    method: 'POST',
                    body: JSON.stringify({
                        fecha: fecha,
                        limit: lugar == 2 ? '5' : ''
                    }),
                    headers: {
                        'content-type': 'application/json',
                    }
                });

                const data = await response.json();

                if (lugar == 1) {
                    var divs = '';
                    if (data == '') {
                        divs += `Vacio, no hay tramos disponibles`;
                    } else {
                        for (var tramos in data) {
                            // console.log(tramos)
                            divs += `<div class="col-2 mb-2">
                                <a data-bs-dismiss="modal" onclick="seleccionTramo(this, '1', \'${data[tramos].fecha}\')" class="btn btn-primary px-4 py-1 rounded-pill boton">${data[tramos].tramos}</a>
                            </div>`;
                        }
                    }

                    tramo.innerHTML = divs;
                }

                var fechaFormateada = formatearFecha(data[tramos].fecha);

                if (data != '') {
                    inputFecha.min = data[tramos].fecha;
                    inputFecha.max = data[tramos].fecha[data[tramos].fecha.length - 1];
                    document.querySelector('#span_tramo').innerHTML =
                        `${data[0].fecha == fecha ? 'Hoy' : fechaFormateada}, ${data[tramos].tramos}hs`;
                    document.querySelector('#fehca_visualizacion').innerHTML =
                        `Se visualizara el ${fechaFormateada} - ${data[0].tramos} hs`;
                    document.getElementById('tramo_select').value = data[0].tramos;

                }


            } catch (error) {
                console.error('Error en buscarTramos:', error);
            }
        }

        function seleccionTramo(tramo, lugar, fecha) {
            var textoDelEnlace = tramo.innerText || tramo.textContent;

            var fechaFormateada = formatearFecha(fecha);
            if (lugar == 1) {
                document.querySelector('#span_tramo').innerHTML = `${fechaFormateada}, ${textoDelEnlace} hs`;
                document.querySelector('#fehca_visualizacion').innerHTML =
                    `Se visualizara el ${fechaFormateada} - ${textoDelEnlace} hs`;
                document.getElementById('tramo_select').value = textoDelEnlace
                document.querySelector('#selectTramo').classList.remove('disabled');
                document.querySelector('#pagar').classList.remove('disabled');
            } else {
                document.querySelector('#span_tramo').innerHTML = `${fechaFormateada}, ${textoDelEnlace} hs`;
                document.getElementById('tramo_select').value = textoDelEnlace
                document.querySelector('#fehca_visualizacion').innerHTML =
                    `Se visualizara el ${fechaFormateada} - ${textoDelEnlace} hs`;
                document.querySelector('#selectTramo').classList.remove('disabled');
                document.querySelector('#pagar').classList.remove('disabled');
            }
        }


        document.getElementById('file-upload').addEventListener('submit', function(event) {
            event.preventDefault();

            document.querySelector('.progress').style.display = 'block';
            var progressBar = document.getElementById('progressBar');
            progressBar.style.width = '0%';
            progressBar.innerHTML = '0%';

            const formData = new FormData(this);

            fetch('{{ route('sale.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    progressBar.style.width = '100%';
                    progressBar.innerHTML = '100%';
                    return response.json();
                })
                .then(data => {
                    // console.log(data)
                    document.querySelector('.progress').style.display = 'none';
                    if (data.status === 1) {
                        document.getElementById('panel-alert').innerHTML = `
                            <div class="alert alert-success" id='miAlerta' alert-dismissible fade show" role="alert">
                            Se subio con exito
                        </div>`
                        document.getElementById('mediaaas').innerHTML =
                            `<div class="col-3"><img class='img-thumbnail' width='200px' heigth='200px' src="${data.img}" alt=""></div>`
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


        function formatearFecha(fechaOriginal) {
            var fecha = new Date(fechaOriginal + 'T00:00:00-04:00');
            var options = {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                timeZone: 'America/Caracas'
            };
            var formatoFecha = new Intl.DateTimeFormat('es-ES', options);
            return formatoFecha.format(fecha);
        }
    </script>
@endsection
