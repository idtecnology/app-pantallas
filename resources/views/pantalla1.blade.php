@extends('layouts.app')

@section('content')
    <div hidden id="spinner"></div>
    <div class="border border-2">
        <div class="">
            <img class="img-fluid" src="https://placehold.co/600x400" alt="">
        </div>
        <div class="d-flex flex-column">
            <div class="p-2">
                <h1 class="mt-2">{{ $screen->name }}</h1>
                <span class="fs-4">{{ $screen->location }}</span>
                <div class="row mt-4">
                    <div class="col-sm-6 col-xs-12">
                        <select class="form-select rounded-pill" name="" id="tiempo">
                            @foreach ($prices as $price)
                                <option value="{{ $price['seconds'] }}">{{ $price['seconds'] }} seg -
                                    ${{ $price['amount'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6 col-xs-12 mt-2">
                        <a onclick="openFiles()" id="mienlace" class="btn btn-primary w-100 rounded-pill text-center">
                            <div class="d-flex align-middle justify-content-center">
                                <span class="material-symbols-outlined md-18 ">
                                    photo_camera
                                </span>
                                <span class="ms-3">Sube tu contenido</span>
                            </div>

                        </a>
                        <input style="display: none" type="file" id="archivos" multiple>
                    </div>
                </div>


            </div>
            <div class="ms-2 mt-4">
                <p class="fw-bold">¡Publicar nunca fue tan fácil!</p>
                <p>Subí tus fotos o un video y publicalo en nuestra pantalla</p>
                <p class="d-flex align-middle ">
                    <span class="material-symbols-outlined fs-4 text-danger">
                        cancel
                    </span>
                    <span class="ms-2">No Permitido</span>
                </p>
                <div class="d-flex flex-wrap p-2">
                    <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-pill">Infraccion por derechos de autor</span>
                    <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-pill">Contenido perturbador</span>
                    <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-pill">Contenido ilegal o peligroso</span>
                    <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-pill">Contenido publicitario engañoso</span>

                    <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-pill">Contenido ofensivo</span>
                    <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-pill">Contenido sexual explicito</span>
                    <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-pill">Difamacion o calumnia</span>
                    <span class="px-2 py-1 my-1 mx-2 bg-warning rounded-pill">Violencia grafica</span>
                </div>
            </div>
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
