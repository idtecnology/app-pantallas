@extends('layouts.app')



@section('content')
    <div class="col-xs-12 mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-6 mb-3">
                        <input class="form-control" id="fecha_programacion" type="date">
                    </div>
                    <div class="col-xs-12 mb-3">
                        <select class='form-select' name="screen_id" id="screen_id">

                            <option value="0">Seleccione</option>
                            @foreach ($pos as $p)
                                <option value="{{ $p->_id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <button onclick="consultaProgramacion();" class="btn btn-sm btn-primary">Consultar</button>
                        <button class="btn btn-warning btn-sm">Limpiar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-12">


    </div>
@endsection

@section('js')
    <script>
        const csrfToken = "{{ csrf_token() }}";

        function consultaProgramacion() {

            var fecha = document.querySelector('#fecha_programacion').value
            var pos = document.querySelector('#screen_id').value




            fetch("{{ route('search-programation') }}", {
                    method: 'POST',
                    body: JSON.stringify({
                        fecha: fecha,
                        screen_id: pos
                    }),
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }
    </script>
@endsection
