@extends('layouts.app')


@section('content')
    <div hidden id="spinner"></div>
    {{-- @dd(session('screen_id')) --}}
    {!! Form::open([
        'route' => 'sale.store',
        'method' => 'POST',
        'id' => 'file-upload',
        'enctype' => 'multipart/form-data',
    ]) !!}

    <input type="hidden" id="tramo_select" name="tramo_select" />
    <input type="hidden" id="screen_id" name="screen_id" value="{{ $id }}" />
    <input type="hidden" id="duration" name="duration" value="{{ $time }}" />
    <input type="hidden" id="date_hidden" name="fecha" value="" />
    <input type="hidden" id="preference" name="preference" value="{{ $preference_id }}" />
    <input type="hidden" id="preference" name="media_id" value="{{ $media_id }}" />
    <input type="hidden" id="amount" name="amount" value="{{ $datas->prices['amount'] }}" />

    <div class="" id="multimedia">
        <span class="fs-4 fw-bold">Tu Contenido</span>
        <input style="display: none;" type="file" name="file[]" id="archivos" accept="image/*,video/*" multiple>
        <div id="archivosPrevisualizacion"></div>
        <div class="row ms-1 mt-4 text-center justify-content-center" id="mediaaas">

            @foreach ($arr as $llave => $ext)
                <div class="col-4">
                    @if ($ext == 'mp4')
                        <div class="col-3"><video class="img-fluid img-thumbnail" width="320" height="240" controls>
                                <source src="{{ asset($rutaLocal[$llave]) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video></div>
                    @else
                        <img class="img-fluid img-thumbnail" width="200px" height="200px"
                            src="{{ asset($rutaLocal[$llave]) }}" alt="">
                    @endif
                </div>
            @endforeach



        </div>
        <div class="w-100 my-4">
            <a onclick="openFiles()"
                class="btn btn-primary rounded-pill d-flex text-center align-middle  justify-content-center">
                <span class="material-symbols-outlined">
                    edit
                </span>
                <span class="ms-3">Edita tu contenido</span></a>
        </div>
    </div>

    <div id="horario-text" class="mb-2 d-flex flex-column">
        <span class="fs-4 mb-1 fw-bold">Elegí tu horario</span>
        <span class="fs-6">Tu publicación saldrá dentro de los 5 minutos siguientes al horario seleccionado.</span>
    </div>
    <div id="horario-select" class="">
        <div class="d-flex justify-content-between">
            <span class="fw-bold">Horario seleccionado:</span>
            <span class="align-middle fs-5" id="span_tramo">Hoy, 00:00 hs</span>
        </div>
        <div class="mt-2 row text-center justify-content-center" id="tramo_fuera">

        </div>

        <div class="mt-2 text-center px-2 py-2 shadow-sm border border-1">
            <a data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="">Ver horarios
                disponibles</a>
        </div>
    </div>



    <div id="resumen" class="my-4">
        <span class="fw-bold fs-4">
            Resumen de tu compra
        </span>
        <div class="d-flex flex-column">
            <span>Tu publicacion sera de {{ $time }} segundos</span>
            <span id="fehca_visualizacion"></span>
            <span>Total: ${{ $datas->prices['amount'] }}
            </span>
        </div>
        <div class="mt-2"><button type="submit" id="pagar"
                class="btn btn-primary rounded-pill w-100 d-flex align-middle justify-content-center">
                <span class="material-symbols-outlined">
                    credit_card
                </span>
                <span class="ms-3">Ir a pagar</span>
            </button></div>

    </div>
    {!! Form::close() !!}



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
                        <input onchange="buscarTramos(this.value, 1)" type="date" name="date" id="inputFechaModal"
                            class="form-control">

                    </div>
                    <div id="diasDisponibles" class="mt-4 p-3 border border-1">
                        <div class="row text-center justify-content-center" style="height:55vh;overflow-y: auto;"
                            id="tramo_modal"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        const spinner = document.getElementById("spinner");
        const csrfToken = "{{ csrf_token() }}";

        document.getElementById('pagar').addEventListener('click', function(event) {
            spinner.removeAttribute('hidden');
        });

        var seleccionarArchivos = document.getElementById('archivos');
        var archivosPrevisualizacion = document.getElementById('archivosPrevisualizacion');
        var mediaaas = document.getElementById('mediaaas');
        var duracionTotal = 0;


        seleccionarArchivos.addEventListener('change', function(event) {
            archivosPrevisualizacion.innerHTML = '';
            mediaaas.innerHTML = '';
            duracionTotal = 0;


            var promesas = [];


            var rowContainer = document.createElement('div');
            rowContainer.classList.add('row');
            for (var i = 0; i < event.target.files.length; i++) {
                var archivo = event.target.files[i];


                var objetoURL = URL.createObjectURL(archivo);

                // Crear un elemento de imagen o video según el tipo de archivo
                var elementoMedia;
                if (archivo.type.startsWith('image')) {
                    elementoMedia = document.createElement('img');
                    elementoMedia.classList.add('img-thumbnail');
                    elementoMedia.style.maxWidth = '200px'; // Establecer el ancho máximo
                    elementoMedia.style.maxHeight = '200px';
                    // Añadir el tiempo específico para imágenes (1.5 segundos)
                    duracionTotal += 1.5;
                } else if (archivo.type.startsWith('video')) {
                    elementoMedia = document.createElement('video');
                    // Añadir el video al array de promesas
                    promesas.push(cargarDuracionVideo(elementoMedia));
                    elementoMedia.style.maxWidth = '200px'; // Establecer el ancho máximo
                    elementoMedia.style.maxHeight = '200px';
                }

                elementoMedia.src = objetoURL;
                elementoMedia.width = 200; // Establecer el ancho según tus necesidades
                elementoMedia.controls = true; // Mostrar controles de reproducción para videos

                var colContainer = document.createElement('div');
                colContainer.classList.add('col-md-4');
                colContainer.classList.add('col-xs-12');
                colContainer.classList.add('col-sm-6'); // Agregar clase de Bootstrap 'col-12'

                colContainer.appendChild(elementoMedia);

                rowContainer.appendChild(colContainer);
            }

            archivosPrevisualizacion.appendChild(rowContainer);

            Promise.all(promesas)
                .then(function(duraciones) {
                    duraciones.forEach(function(duracion) {
                        duracionTotal += duracion;
                    });
                    verificarDuracionTotal();
                })
                .catch(function(error) {
                    console.error('Error al cargar metadatos de videos:', error);
                });

            // agregar fecth con el update. 

            var inputArchivos = document.getElementById('archivos');

            // Crear un objeto FormData y agregar los archivos seleccionados
            var formData = new FormData();
            for (var i = 0; i < inputArchivos.files.length; i++) {
                formData.append('archivos[]', inputArchivos.files[i]);
            }

            formData.append('screen_id', {{ $id }});
            formData.append('tiempo', {{ $time }});
            formData.append('media_id', {{ $media_id }});


            fetch("/guardarData", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status == 0) {
                        archivosPrevisualizacion.innerHTML = '';
                        spinner.setAttribute('hidden', '');
                        alert(data.error)
                        document.querySelector('#archivos').click();
                    }
                    console.log('Respuesta del servidor:', data);
                })
                .catch(error => {
                    // Manejar errores de la solicitud
                    console.error('Error:', error);
                });



        });

        function cargarDuracionVideo(video) {
            return new Promise(function(resolve, reject) {
                video.addEventListener('loadedmetadata', function() {
                    console.log(video.duration)
                    resolve(video.duration);
                });
                video.addEventListener('error', function(event) {
                    reject(event.error);
                });
            });
        }

        function verificarDuracionTotal() {
            if (duracionTotal > {{ $time }}) {
                alert('La duración total supera los ' + {{ $time }} +
                    ' segundos seleccionados. Por favor, ajusta tus archivos.');
                archivosPrevisualizacion.innerHTML = '';
                duracionTotal = 0;
            }
        }
    </script>
    <script>
        buscarTramos("{{ date('Y-m-d') }}", 2)
        getAvailabilityDates()


        function openFiles() {
            document.querySelector('#archivos').click();
        }

        async function getAvailabilityDates() {
            var inputFechaModal = document.querySelector('#inputFechaModal');
            const response = await fetch('/api/availability-dates', {
                method: 'POST',
                body: JSON.stringify({
                    duration: {{ $time }},
                    screen_id: {{ $id }}
                }),
                headers: {
                    'content-type': 'application/json',
                }
            });
            const data = await response.json();
            const count = data.length - 1
            inputFechaModal.min = data[0].fecha;
            inputFechaModal.max = data[count].fecha;
        }


        async function buscarTramos(fecha, lugar) {
            try {
                var tramo_modal = document.querySelector('#tramo_modal');
                var tramo_fuera = document.querySelector('#tramo_fuera');
                var divs = '';

                document.getElementById('date_hidden').value = fecha;

                const response = await fetch('/api/tramo', {
                    method: 'POST',
                    body: JSON.stringify({
                        fecha: fecha,
                        duration: {{ $time }},
                        screen_id: {{ $id }}
                    }),
                    headers: {
                        'content-type': 'application/json',
                    }
                });

                const data = await response.json();

                if (lugar == 1) {
                    if (data == '') {
                        divs += `Vacio, no hay tramos disponibles`;
                    } else {
                        for (var tramos in data) {
                            divs += `<div class="col-3 px-0">
                                <a data-bs-dismiss="modal" onclick="seleccionTramo(this, '1', \'${data[tramos].fecha}\')" class="btn btn-primary mb-2">${cambiarFormatoHora(data[tramos].tramos)}</a>
                            </div>`;
                        }
                    }
                    tramo_modal.innerHTML = divs;
                } else {
                    if (data == '') {
                        divs += `Vacio, no hay tramos disponibles`;
                    } else {
                        for (var i = 0; i < 10; i++) {
                            divs += `
                                <a onclick="seleccionTramo(this, '1', \'${data[i].fecha}\')" class="btn btn-primary btn-sm mb-2 mx-1 col-2">${cambiarFormatoHora(data[i].tramos)}</a>
                            `;
                        }
                    }
                    tramo_fuera.innerHTML = divs;
                }



                if (data != '') {
                    var fechaFormateada = formatearFecha(data[0].fecha);
                    document.querySelector('#span_tramo').innerHTML =
                        `${data[0].fecha == fecha ? 'Hoy' : fechaFormateada}, ${cambiarFormatoHora(data[0].tramos)} hs`;
                    document.querySelector('#fehca_visualizacion').innerHTML =
                        `Se visualizara el ${fechaFormateada} - ${cambiarFormatoHora(data[0].tramos)} hs`;
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
                document.querySelector('#pagar').classList.remove('disabled');
            } else {
                document.querySelector('#span_tramo').innerHTML = `${fechaFormateada}, ${textoDelEnlace} hs`;
                document.getElementById('tramo_select').value = textoDelEnlace
                document.querySelector('#fehca_visualizacion').innerHTML =
                    `Se visualizara el ${fechaFormateada} - ${textoDelEnlace} hs`;
                document.querySelector('#pagar').classList.remove('disabled');
            }
        }

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

        function cambiarFormatoHora(horaOriginal) {
            var fecha = new Date('2000-01-01T' + horaOriginal);

            var horas = fecha.getHours();
            var minutos = fecha.getMinutes();

            var horasFormateadas = horas < 10 ? '0' + horas : horas;
            var minutosFormateados = minutos < 10 ? '0' + minutos : minutos;

            return horasFormateadas + ':' + minutosFormateados;
        }
    </script>
@endsection
