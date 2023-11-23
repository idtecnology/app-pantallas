@extends('layouts.app')


@section('content')
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
    <input type="hidden" id="fechasss" name="fecha" value="" />
    <input type="hidden" id="preference" name="preference" value="{{ $preference_id }}" />
    <input type="hidden" id="preference" name="media_id" value="{{ $media_id }}" />
    <div id="horario-text" class="mb-2 d-flex flex-column">
        <span class="fs-4 mb-2">Elegí tu horario</span>
        <span class="fs-6">Tu publicación saldrá dentro de los 5 minutos siguientes al horario seleccionado.</span>
    </div>
    <div id="horario-select" class="">
        <div class="d-flex justify-content-between">
            <span class="fw-bold">Horario seleccionado:</span>
            <span class="align-middle" id="span_tramo">Hoy, 00:00 hs</span>
        </div>

        <div class="mt-2 text-center px-2 py-2 shadow-sm border border-1">
            <a data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="">Ver horarios
                disponibles</a>
        </div>
    </div>

    <div class="mt-4" id="multimedia">
        <span class="fs-4">Tu multimedia</span>
        <input type="file" name="file[]" id="archivos" accept="image/*,video/*" multiple>
        <div id="archivosPrevisualizacion"></div>
        <div class="row ms-1 mt-4 text-center" id="mediaaas">
            <div class="col-4">
                @if ($extension == 'mp4')
                    <div class="col-3"><video width="320" height="240" controls>
                            <source src="{{ asset($rutaLocal) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video></div>
                @else
                    <img class="img-fluid" width="200px" height="200px" src="{{ asset($rutaLocal) }}" alt="">
                @endif

            </div>
        </div>
        <div class="w-100 mt-4">
            <a data-bs-toggle="modal" id='selectTramo' data-bs-toggle="modal" data-bs-target="#staticBackdro2"
                class="btn btn-primary rounded-pill d-flex text-center align-middle  justify-content-center">
                <span class="material-symbols-outlined">
                    edit
                </span>
                <span class="ms-3">Edita tu contenido</span></a>
        </div>
    </div>

    <div id="resumen" class="mt-4">
        <span class="fw-bold fs-4">
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
        <div class="mt-2"><button type="submit" id="pagar"
                class="btn btn-primary rounded-pill w-100 d-flex align-middle disabled justify-content-center">
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
@endsection


@section('js')
    <script>
        var seleccionarArchivos = document.getElementById('archivos');
        var archivosPrevisualizacion = document.getElementById('archivosPrevisualizacion');
        var mediaaas = document.getElementById('mediaaas');
        var duracionTotal = 0;

        seleccionarArchivos.addEventListener('change', function(event) {
            archivosPrevisualizacion.innerHTML = '';
            mediaaas.innerHTML = '';
            duracionTotal = 0; // Reiniciar la duración total

            // Crear un array de promesas para manejar la carga de los metadatos de los videos
            var promesas = [];

            // Crear un div contenedor principal de tipo row
            var rowContainer = document.createElement('div');
            rowContainer.classList.add('row'); // Agregar clase de Bootstrap 'row'

            for (var i = 0; i < event.target.files.length; i++) {
                var archivo = event.target.files[i];

                // Crear un objeto URL para el archivo seleccionado
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

                // Crear un div contenedor de tipo col-12
                var colContainer = document.createElement('div');
                colContainer.classList.add('col-md-4');
                colContainer.classList.add('col-xs-12');
                colContainer.classList.add('col-sm-6'); // Agregar clase de Bootstrap 'col-12'

                // Agregar el elementoMedia al div contenedor de tipo col-12
                colContainer.appendChild(elementoMedia);

                // Agregar el div contenedor de tipo col-12 al div contenedor principal
                rowContainer.appendChild(colContainer);
            }

            // Agregar el div contenedor principal al contenedor
            archivosPrevisualizacion.appendChild(rowContainer);

            // Verificar la duración total después de agregar todos los elementos
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
                    // Manejar la respuesta del servidor si es necesario
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
                    resolve(video.duration);
                });
                video.addEventListener('error', function(event) {
                    reject(event.error);
                });
            });
        }

        function verificarDuracionTotal() {
            console.log(duracionTotal)
            if (duracionTotal > 600) {
                alert('La duración total supera los 30 segundos. Por favor, ajusta tus archivos.');
                // Limpiar la lista de previsualización y reiniciar la duración total
                archivosPrevisualizacion.innerHTML = '';
                duracionTotal = 0;
            }
        }
    </script>
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
                        limit: lugar == 2 ? '5' : '',
                        duration: {{ $time }}
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
                            divs += `<div class="col-4 px-0">
                                <a data-bs-dismiss="modal" onclick="seleccionTramo(this, '1', \'${data[tramos].fecha}\')" class="btn btn-primary mb-2">${data[tramos].tramos}</a>
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
                        `${data[0].fecha == fecha ? 'Hoy' : fechaFormateada}, ${data[0].tramos}hs`;
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





        // document.getElementById('file-upload').addEventListener('submit', function(event) {
        //     event.preventDefault();


        //     const formData = new FormData(this);

        //     fetch('{{ route('sale.store') }}', {
        //             method: 'POST',
        //             body: formData,
        //             headers: {
        //                 'X-CSRF-TOKEN': csrfToken
        //             }
        //         })
        //         .then(response => {
        //             return response.json();
        //         })
        //         .then(data => {
        //             console.log(data)
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //         });
        // });


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
