@extends('layouts.app')

@section('content')
    <div class="border border-2">
        <div class="">
            <img class="img-fluid" src="https://placehold.co/292x170" alt="">
        </div>
        <div class="d-flex flex-column">
            <div class="p-2">
                <h1 class="mt-2">Title</h1>
                <span class="fs-4">Address</span>
                <div class="row mt-4">
                    <div class="col-sm-6 col-xs-12">
                        <select onchange="tiempo(this.value)" class="form-select rounded-pill" name=""
                            id="">
                            <option value="15">15 seg - $10.000</option>
                            <option value="30">30 seg - $20.000</option>
                            <option value="45">45 seg - $30.000</option>
                            <option value="60">60 seg - $40.000</option>
                            <option value="120">120 seg - $80.000</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-xs-12 mt-2">
                        <a id="mienlace" class="btn btn-primary w-100 rounded-pill text-center">
                            <div class="d-flex align-middle justify-content-center">
                                <span class="material-symbols-outlined md-18 ">
                                    photo_camera
                                </span>
                                <span class="ms-3">Sube tu contenido</span>
                            </div>

                        </a>
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

    <script>
        tiempo()

        function tiempo(segundos = 15) {
            document.getElementById('mienlace').href = `/p2/1/${segundos}`

        }
    </script>
@endsection
