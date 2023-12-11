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
    <div class="col-xs-12 text-center">
        <div id="programacion">
            <table class="table table-sm mb-0 mt-3  table-bordered">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Campania/Email</th>
                        <th>Duracion</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>

        </div>
        <div class="mt-3" id="pag">
            <button class="btn btn-sm btn-primary" id="prevButton"> pre </button>
            <button class="btn btn-sm btn-primary" id="nextButton"> next </button>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const csrfToken = "{{ csrf_token() }}";
        var prevButton = document.getElementById('prevButton');
        var nextButton = document.getElementById('nextButton');
        var datos;
        var totalPages;

        function consultaProgramacion(page = 0) {
            var tableBody = document.querySelector('#tableBody')

            tableBody.innerHTML = ''
            var fecha = document.querySelector('#fecha_programacion')
            var pos = document.querySelector('#screen_id')
            // var page = page
            var itemsPerPage = 10;






            fetch("/api/search-programation?page=" + page + " &itemsPerPage=" + itemsPerPage, {
                    method: 'POST',
                    body: JSON.stringify({
                        fecha: fecha.value,
                        screen_id: pos.value
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data.data.length > 0) {
                        datos = data.data
                        updateUI(data);
                        updatePagination(data);
                    } else {
                        tableBody.innerHTML = `<div class="alert alert-danger mt-4" role="alert">Sin Registros</div>`
                    }




                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }

        function updateUI(data) {


            // var datos = data.data.data
            var arr3 = data.arr3
            var arr1 = data.arr1
            const tableBody = document.getElementById('tableBody');

            data.arr3.forEach(time => {
                const timeRow = document.createElement('tr');
                const timeCell = document.createElement('td');
                timeCell.setAttribute('rowspan', data.arr1[time].length + 1);
                timeCell.classList = 'align-middle'
                timeCell.textContent = time;
                timeRow.appendChild(timeCell);
                tableBody.appendChild(timeRow);

                data.arr1[time].forEach(dato => {
                    const row = document.createElement('tr');

                    const campaniaEmailCell = document.createElement('td');
                    campaniaEmailCell.textContent = dato.campania_name ? dato.campania_name : dato.email;
                    row.appendChild(campaniaEmailCell);

                    const reproducidoCell = document.createElement('td');
                    reproducidoCell.textContent = dato.media_duration === null ? '15 segundos' : `${dato
                        .media_duration} segundos`;
                    row.appendChild(reproducidoCell);

                    const estadoCell = document.createElement('td');
                    const switchDiv = document.createElement('div');
                    switchDiv.classList.add('form-check', 'form-switch');

                    const switchInput = document.createElement('input');
                    switchInput.setAttribute('id', `check_${dato.media_id}`);
                    switchInput.setAttribute('onchange', `disabledMedia(${dato.media_id});`);
                    switchInput.setAttribute('type', 'checkbox');
                    switchInput.setAttribute('role', 'switch');
                    switchInput.checked = dato.media_isActive === 1;

                    if (new Date().getHours() >= parseInt(dato.media_time.split(':')[0]) &&
                        new Date().toDateString() === new Date(dato.media_date).toDateString()) {
                        switchInput.setAttribute('disabled', true);
                    }

                    console.log(dato.media_date)


                    const switchLabel = document.createElement('label');
                    switchLabel.setAttribute('id', `label_check_${dato.media_id}`);
                    switchLabel.classList.add('form-check-label');
                    switchLabel.setAttribute('for', `check_${dato.media_id}`);
                    switchLabel.textContent = dato.media_isActive === 1 ? 'Activo' : 'Inactivo';

                    switchDiv.appendChild(switchInput);
                    switchDiv.appendChild(switchLabel);
                    estadoCell.appendChild(switchDiv);
                    row.appendChild(estadoCell);

                    tableBody.appendChild(row);
                });
            });


            var paginacion = document.querySelector('#pag')
            pag.style.display = '';

        }


        console.log(datos)

        function updatePagination(data) {
            // var datos = data.data

            totalPages = datos.last_page;





            // Puedes deshabilitar los botones si estás en la primera o última página
            prevButton.disabled = (datos.current_page === 1);
            nextButton.disabled = (datos.current_page === totalPages);
        }


        prevButton.addEventListener('click', function() {
            if (datos.current_page > 1) {
                datos.current_page--;
                consultaProgramacion(datos.current_page);
            }
        });

        nextButton.addEventListener('click', function() {
            if (datos.current_page < totalPages) {
                datos.current_page++;
                consultaProgramacion(datos.current_page);
            }
        });
    </script>
@endsection
