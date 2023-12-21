@extends('layouts.app')

@section('content')
    <div hidden id="spinner"></div>

    <div class="px-0 position-relative">
        <style>
            .imagen-con-degradado {
                background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0)),
                    url('{{ $screen->imagen }}');
                background-size: cover;
                background-position: 50%;
                width: 100%;
                max-width: 100vw;
                height: 300px;
                border-radius: 0 0 2rem 2rem;
            }
        </style>
        <div class="imagen-con-degradado"></div>
        <h1 class="mt-2 text-1">{{ $screen->nombre }}</h1>
        <span class="text-2">{{ $screen->direccion }}</span>
        <div class="row selects-price mx-0">
            <div class="w-50">
                <select class="form-select rounded-pill bg-primario text-white shortened-select" name="" id="tiempo">
                    @foreach ($prices as $price)
                        <option value="{{ $price['seconds'] }}" data-descr="{{ $price['amount'] }}">
                            {{ $price['seconds'] }} Segundos

                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-50">
                <a onclick="openFiles()" id="mienlace" class="btn btn-primary w-100 rounded-pill text-center">
                    <div class="d-flex align-middle justify-content-center align-items-center">
                        <span class="material-symbols-outlined md-18 ">
                            photo_camera
                        </span>
                        <span class="ml-4p">Agrega contenido</span>
                    </div>

                </a>
                <input style="display: none" type="file" id="archivos" accept="image/*,video/*" multiple>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column">
        <div class="ms-2 mt-3">
            <p class="fw-bold mb-1">¡Publicar nunca fue tan fácil!</p>
            <p>Subí tus fotos o un video y publicalo en nuestra pantalla</p>
            <p class="d-flex align-items-center">
                <span class="material-symbols-outlined fs-4 text-danger">
                    cancel
                </span>
                <span class="ms-1 fw-bold">No Permitido</span>
            </p>
            <div class="d-flex flex-wrap p-2">
                <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-2">Infraccion por derechos de autor</span>
                <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-2">Contenido perturbador</span>
                <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-2">Contenido ilegal o peligroso</span>
                <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-2">Contenido publicitario engañoso</span>

                <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-2">Contenido ofensivo</span>
                <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-2">Contenido sexual explicito</span>
                <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-2">Difamacion o calumnia</span>
                <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-2">Violencia grafica</span>
            </div>
            <p>Mira estas <a target="_blank" href="https://adsupp.com/recomendaciones">recomendaciones</a> para tu
                publicación</p>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function updateOptionTexts(select, showPrice) {
            [].forEach.call(select.options, function(o) {
                if (showPrice) {
                    o.textContent = o.getAttribute('value') + ' Segundos - $' + o.getAttribute('data-descr');
                } else {
                    o.textContent = o.getAttribute('value') + ' Segundos';
                }
            });
        }

        document.querySelectorAll('.shortened-select').forEach(function(select) {
            select.addEventListener('change', function() {
                updateOptionTexts(select, false);
            });

            select.addEventListener('touchstart', function() {
                updateOptionTexts(select, true);
            });

            select.addEventListener('click', function() {
                updateOptionTexts(select, true);
            });

            select.addEventListener('blur', function() {
                setTimeout(function() {
                    updateOptionTexts(select, false);
                }, 0);
            });

            updateOptionTexts(select, false);
        });



        document.getElementById('archivos').addEventListener('click', function(event) {
            if (checkSess != 1) {
                event.preventDefault();
                window.location.href = '/login';
            }
        });



        document.getElementById('archivos').addEventListener('change', function() {

            var inputArchivos = document.getElementById('archivos');
            tiempo = document.getElementById('tiempo').value;

            // Crear un objeto FormData y agregar los archivos seleccionados
            var formData = new FormData();
            for (var i = 0; i < inputArchivos.files.length; i++) {
                formData.append('archivos[]', inputArchivos.files[i]);
            }

            formData.append('screen_id', {{ $id }});
            formData.append('tiempo', tiempo);
            formData.append('client_id', {{ auth()->id() }});

            notifySpinner({
                title: 'Cargando datos!',
                html: 'Se estan comprobando tus archivos',
                allowOutsideClick: false,
                showLoaderOnConfirm: false,
            });

            fetch("/api/guardarData", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.hideLoading()
                    if (data.status == 0) {
                        Swal.hideLoading()

                        notifyGeneral({
                            title: 'Cargando datos!',
                            text: data.mensaje,
                            icon: 'error'
                        })

                        document.getElementById('archivos').value = ''
                    } else {
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/p2';

                        var csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;

                        var screenIdInput = document.createElement('input');
                        screenIdInput.type = 'hidden';
                        screenIdInput.name = 'screen_id';
                        screenIdInput.value = {{ $id }};

                        var tiempoInput = document.createElement('input');
                        tiempoInput.type = 'hidden';
                        tiempoInput.name = 'tiempo';
                        tiempoInput.value = tiempo;

                        var mediaIdInput = document.createElement('input');
                        mediaIdInput.type = 'hidden';
                        mediaIdInput.name = 'media_id';
                        mediaIdInput.value = data.media_id;

                        form.appendChild(csrfInput);
                        form.appendChild(screenIdInput);
                        form.appendChild(tiempoInput);
                        form.appendChild(mediaIdInput);

                        document.body.appendChild(form);
                        form.submit();
                    }

                })
                .catch(error => {
                    // Manejar errores de la solicitud
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
