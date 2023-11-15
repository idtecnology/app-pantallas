@extends('layouts.app')
@section('content')
    <div class="col-12">
        {!! Form::open(['route' => 'sale.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-8 mb-3">
                <div class="form-group">
                    <strong>Nombre:</strong>
                    {!! Form::text('name', null, ['placeholder' => 'Nombre', 'class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-4 mb-3 align-middle">
                <div class="form-group">
                    <strong>Tipo:</strong>
                    <div class="form-check form-check-inline ms-3">
                        <input class="form-check-input" type="radio" value="1" name="type" id="inlineRadio1"
                            value="option1">
                        <label class="form-check-label" for="inlineRadio1">Video</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" value="2" name="type" id="inlineRadio2"
                            value="option2">
                        <label class="form-check-label" for="inlineRadio2">Slideshow</label>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Punto de venta:</strong>
                    {!! Form::select('screen_id', $pos, ['placeholder' => 'Seleccione', 'class' => 'form-select']) !!}
                </div>
            </div>
            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Fecha:</strong>
                    <input onchange="buscarTramos(this.value, 2)" type="date" min='today' name='fecha'
                        class="form-control">
                </div>
            </div>

            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>Tramos:</strong>
                    <div class="row" id="tramo"></div>
                </div>
            </div>

            <div class="col-4 mb-3">
                <div class="form-group">
                    <strong>duracion:</strong>
                    <select class="form-select rounded-pill" name="duration" id="">
                        <option value="15">15 seg - $10.000</option>
                        <option value="30">30 seg - $20.000</option>
                        <option value="45">45 seg - $30.000</option>
                        <option value="60">60 seg - $40.000</option>
                        <option value="120">120 seg - $80.000</option>
                    </select>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="form-group">
                    <strong>Multimedia:</strong>
                    <input type="file" name='files[]' multiple class="form-control">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <script>
        buscarTramos("{{ date('Y-m-d') }}", 2)

        function buscarTramos(fecha, lugar) {

            var tramo = document.querySelector('#tramo')
            tramo.innerHTML = ''

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
                    var divs = '';
                    for (tramos in data) {
                        divs += `<div class="form-check form-check-inline">
                        <input class="form-check-input" name="tramos[]" type="checkbox" value="${data[tramos].tramos}">
                        <label class="form-check-label" for="inlineCheckbox1">${data[tramos].tramos}</label>
                        </div>`
                        tramo.innerHTML = divs;
                    }

                });
        }
    </script>
@endsection
