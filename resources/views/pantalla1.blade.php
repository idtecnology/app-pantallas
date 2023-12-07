@extends('layouts.app')

@section('content')
    <div hidden id="spinner"></div>

    <div class=" position-relative">
        <img width="426px" class="img-fluid rounded-bottom-5" src="{{ $screen->imagen }}" alt="">
        <h1 class="mt-2 text-1">{{ $screen->nombre }}</h1>
        <span class="text-2">{{ $screen->direccion }}</span>
        <div class="row selects-price">
            <div class="w-50">
                <select class="form-select rounded-pill bg-primario text-white" name="" id="tiempo">
                    @foreach ($prices as $price)
                        <option value="{{ $price['seconds'] }}">{{ $price['seconds'] }} seg -
                            ${{ $price['amount'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-50">
                <a onclick="openFiles()" id="mienlace" class="btn btn-primary w-100 rounded-pill text-center">
                    <div class="d-flex align-middle justify-content-center">
                        <span class="material-symbols-outlined md-18 ">
                            photo_camera
                        </span>
                        <span class="ml-4p">Agrega contenido</span>
                    </div>

                </a>
                <input style="display: none" type="file" id="archivos" multiple>
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
            <p>Mira estas recomendaciones para tu publicación</p>
        </div>
    </div>

    <input id="checkSess" type="hidden" value="{{ auth()->check() }}">
@endsection

@section('js')
    <script>
        function openFiles() {
            document.querySelector('#archivos').click();
        }

        const spinner = document.getElementById("spinner");
        var checkSess = document.getElementById('checkSess').value;

        document.getElementById('archivos').addEventListener('click', function(event) {
            if (checkSess != 1) {
                event.preventDefault();
                window.location.href = '/login';
            }
        });


        const csrfToken = "{{ csrf_token() }}";
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

            spinner.removeAttribute('hidden');

            fetch("/guardarData", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // console.log(data)
                    if (data.status == 0) {
                        spinner.setAttribute('hidden', '');
                        alert(data.error)
                        document.querySelector('#archivos').click();
                    } else {
                        spinner.setAttribute('hidden', '');
                        var urlConParametro =
                            `/p2/{{ $id }}/${tiempo}/${data.media_id}/${data.preference_id}`
                        window.location.href = urlConParametro;
                    }


                })
                .catch(error => {
                    // Manejar errores de la solicitud
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
