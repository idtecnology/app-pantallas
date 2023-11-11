@extends('layouts.app')


@section('content')
    <div id="multimedia">
        <span class="fs-3 mb-3">Tu multimedia</span>
        <div class="row text-center mt-4">
            <div class="col-3">
                <img src="https://placehold.co/200x200" alt="">
            </div>
            <div class="col-3">
                <img src="https://placehold.co/200x200" alt="">
            </div>
            <div class="col-3">
                <img src="https://placehold.co/200x200" alt="">
            </div>
            <div class="col-3">
                <img src="https://placehold.co/200x200" alt="">
            </div>
        </div>
        <div class="w-100 mt-4">
            <a class="btn btn-primary rounded-pill d-flex text-center align-middle">
                <span class="material-symbols-outlined">
                    edit
                </span>
                <span>edita tu contenido</span></a>
        </div>
    </div>
    <div id="horario-text" class="mt-4 d-flex flex-column">
        <span class="fs-3 mb-3">Elegi tu horario</span>
        <span class="fs-5 mb-3">Tu publicación saldrá dentro de los 5 minutos siguientes al horario seleccionado.</span>
    </div>
    <div id="horario-select" class="mt-4 p-2">
        <div class="d-flex align-middle items-center">
            <span class="fs-3 fw-bold">Horario seleccionado:</span>
            <span class="fs-4">Hoy, 15:30 hs</span>
        </div>
        <div class="row text-center mt-3">
            <div class="col-2"><span class="py-2 px-3 bg-primary rounded-pill text-white">15:10</span></div>
            <div class="col-2"><span class="py-2 px-3 bg-secondary rounded-pill text-white">15:20</span></div>
            <div class="col-2"><span class="py-2 px-3 bg-secondary rounded-pill text-white">15:30</span></div>
            <div class="col-2"><span class="py-2 px-3 bg-secondary rounded-pill text-white">15:40</span></div>
            <div class="col-2"><span class="py-2 px-3 bg-secondary rounded-pill text-white">15:50</span></div>
            <div class="col-2"><span class="py-2 px-3 bg-secondary rounded-pill text-white">16:00</span></div>
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
            <span>tu publicacion sera de 15 segundos</span>
            <span>Se visualizara el 27/10/2023 - 15:30 hs</span>
            <span>Total: $10.000</span>
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
                        <input type="date" name="date" id="date" class="form-control">
                    </div>
                    <div id="diasDisponibles" class="mt-4 p-3 border border-1">
                        <div class="row">
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">07:00</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">07:10</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">07:20</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">07:30</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">07:40</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">07:50</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">08:00</a>
                            </div>

                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">08:10</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">08:20</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">08:30</a>
                            </div>
                            <div class="col-2 mb-2">
                                <a class="btn btn-primary px-4 py-1 rounded-pill">08:40</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
