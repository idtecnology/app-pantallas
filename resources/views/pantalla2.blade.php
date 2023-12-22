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
    <input type="hidden" id="preference" name="preference" value="{{ $datas->preference_id }}" />
    <input type="hidden" id="preference" name="media_id" value="{{ $media_id }}" />
    <input type="hidden" id="amount" name="amount" value="{{ $datas->prices['amount'] }}" />

    <div class="" id="multimedia">
        <span class="fs-4 fw-bold">Tu Contenido</span>
        <input style="display: none;" type="file" name="file[]" id="archivos" accept="image/*,video/*" multiple>
        <div class="row text-center row-gap-3" id="media_container">

            @foreach ($arr as $llave => $ext)
                <div class="col-md-4 col-xs-12 col-sm-6">
                    @if (in_array($ext, config('ext_aviable.EXTENSIONES_PERMITIDAS_VIDEO')))
                        <video class="img-fluid img-thumbnail" width="320" height="240" controls
                            src="{{ asset($rutaLocal[$llave]) }}">
                            Your browser does not support the video tag.
                        </video>
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
                        <input onchange="buscarTramos(this.value, 1, {{ $time }}, {{ $id }})"
                            value="{{ date('Y-m-d') }}" type="date" name="date" id="inputFechaModal"
                            class="form-control">
                    </div>
                    <div id="diasDisponibles" class="mt-4 p-3 border border-1">
                        <div class="row text-center" style="height:55vh;overflow-y: auto;" id="tramo_modal"></div>
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
        buscarTramos("{{ date('Y-m-d') }}", 2, {{ $time }}, {{ $id }})
        buscarTramos("{{ date('Y-m-d') }}", 1, {{ $time }}, {{ $id }})
        getAvailabilityDates({{ $time }}, {{ $id }})

        document.getElementById('pagar').addEventListener('click', function(event) {
            Swal.fire({
                title: 'Procesando datos!',
                html: 'Estamos generando la orden de compra, seras redirigido en un momento.',
                allowOutsideClick: false,
                showLoaderOnConfirm: false,
                didOpen: () => {
                    Swal.showLoading()
                },
            });
        });

        var seleccionarArchivos = document.getElementById('archivos');
        var media_container = document.getElementById('media_container');


        seleccionarArchivos.addEventListener('change', function(event) {
            media_container.innerHTML = '';
            var inputArchivos = document.getElementById('archivos');
            var formData = new FormData();
            for (var i = 0; i < inputArchivos.files.length; i++) {
                formData.append('archivos[]', inputArchivos.files[i]);
            }

            formData.append('screen_id', {{ $id }});
            formData.append('tiempo', {{ $time }});
            formData.append('media_id', {{ $media_id }});
            formData.append('momentun', true);


            notifySpinner({
                title: 'Cargando datos!',
                html: 'Se estan comprobando tus archivos',
                allowOutsideClick: false,
                showLoaderOnConfirm: false,
            });


            var add = '';
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
                        Swal.hideLoading()
                        notifyGeneral({
                            title: 'Cargando datos!',
                            text: data.mensaje,
                            icon: 'error',
                        })
                        document.getElementById('archivos').value = ''
                    }
                    if (data.status == 1) {
                        Swal.hideLoading()
                        for (var i = 0; i < data.files.length; i++) {
                            var link = document.createElement('a');
                            link.href = url + data.files[i].file_name;
                            var pathParts = link.pathname.split('/');
                            var fileName = pathParts[pathParts.length - 1];
                            var fileExtension = fileName.split('.').pop();


                            add += ` <div class="col-md-4 col-xs-12 col-sm-6">`
                            if (extensionesImagen.includes(fileExtension.toLowerCase())) {
                                add +=
                                    `<img class="img-fluid img-thumbnail" width="200px" height="200px" src="${url}${data.files[i].file_name}" alt="">`

                            } else if (extensionesVideo.includes(fileExtension.toLowerCase())) {
                                add += `<video class="img-fluid img-thumbnail" width="320" height="240" src="${url}${data.files[i].file_name}" controls>
                            Your browser does not support the video tag.
                        </video>`
                            } else {
                                return;
                            }
                            add += `</div>`;
                        }
                    }

                    media_container.innerHTML = add;

                })
                .catch(error => {
                    // Manejar errores de la solicitud
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
