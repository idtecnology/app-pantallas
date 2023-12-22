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
                            <option value="">Seleccione</option>
                            @foreach ($pos as $p)
                                <option value="{{ $p->_id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-6">
                        <button onclick="consultaProgramacion();" class="btn btn-sm btn-primary">Consultar</button>
                        <button onclick="limpiar();" class="btn btn-warning btn-sm">Limpiar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-xs-12 text-center">
        <div style="display: none;" id="programacion" class="overflow-scroll">
            <table class="table table-sm mb-0 mt-3  table-bordered">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Campania/Email</th>
                        <th>Duracion</th>
                        <th>Reproducido</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>

        </div>

    </div>
    <div style="display: none;" class="col-12 text-center" id="pag">
        <div class="mt-3 d-flex justify-content-evenly">
            <button class="btn btn-sm btn-primary" id="prevButton"> Anterior </button>
            <button class="btn btn-sm btn-primary" id="nextButton"> Siguiente </button>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var prevButton = document.getElementById('prevButton');
        var nextButton = document.getElementById('nextButton');
        var fecha_programacion = document.querySelector('#fecha_programacion')
        var screen_id = document.querySelector('#screen_id')

        fecha_programacion.focus()
        var datos;
        var totalPages;

        function limpiar() {
            fecha_programacion.value = ''
            screen_id.value = ''
            tableBody.innerHTML = ''
            fecha_programacion.focus()
        }

        function disabledMedia(id) {

            var url = @json(route('disabled-media', ['id' => '__id__']));
            url = url.replace('__id__', id);

            fetch(url, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status == 200) {
                        if (data.isActive == 0) {
                            document.querySelector(`#label_check_${id}`).innerHTML = 'Inactivo'
                            alert(data.message);

                        } else {
                            document.querySelector(`#label_check_${id}`).innerHTML = 'Activo'
                            alert(data.message);
                        }
                    } else {
                        alert(data.message);
                        if (!document.querySelector(`#check_${id}`).checked == true) {
                            document.querySelector(`#check_${id}`).checked = !true
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            setTimeout(function() {
                consultaProgramacion()
            }, 1000);


        }

        function validateInputs() {



            if (fecha_programacion.value == '') {
                Swal.fire('Error en el formulario', 'Debe seleccionar una fecha', 'error')
                fecha_programacion.focus()
                return false;
            }

            if (screen_id.value == '') {
                Swal.fire('Error en el formulario', 'Debe seleccionar una pantalla', 'error')
                screen_id.focus()
                return false;
            }

            return true
        }

        function consultaProgramacion(page = 0) {
            var validate = validateInputs()
            if (validate === true) {
                var tableBody = document.querySelector('#tableBody')
                var programacion = document.querySelector('#programacion')
                programacion.style.display = '';
                tableBody.innerHTML = ''
                var fecha = document.querySelector('#fecha_programacion')
                var pos = document.querySelector('#screen_id')
                // var page = page
                var itemsPerPage = 5;

                fetch("{{ route('search-programation') }}?page=" + page + "&itemsPerPage=" + itemsPerPage, {
                        method: 'POST',
                        body: JSON.stringify({
                            fecha: fecha.value,
                            screen_id: pos.value,
                            itemsPerPage: itemsPerPage
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
                            Swal.fire('Error en el formulario',
                                'No hay registros que coincidan con los datos introducidos',
                                'error')
                            tableBody.innerHTML =

                                `<tr><td colspan='4'><div class="alert alert-danger mt-4" role="alert">Sin Registros</div></td></tr>`
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

        }

        function updateUI(data) {
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
                    campaniaEmailCell.classList = 'align-middle'
                    row.appendChild(campaniaEmailCell);
                    const reproducidoCell = document.createElement('td');
                    reproducidoCell.textContent = dato.media_duration === null ? '15 Segundos' : `${dato
                        .media_duration} Segundos`;
                    row.appendChild(reproducidoCell);
                    const repro = document.createElement('td');
                    repro.textContent = dato.media_reproducido === 1 ? 'Reproducido' : '';
                    row.appendChild(repro);
                    const estadoCell = document.createElement('td');
                    estadoCell.classList = 'align-middle'
                    const switchDiv = document.createElement('div');
                    switchDiv.classList.add('form-check', 'form-switch');
                    const switchInput = document.createElement('input');
                    switchInput.setAttribute('id', `check_${dato.media_id}`);
                    switchInput.setAttribute('onchange', `disabledMedia(${dato.media_id});`);
                    switchInput.setAttribute('type', 'checkbox');
                    switchInput.setAttribute('role', 'switch');
                    switchInput.classList = 'form-check-input'
                    switchInput.checked = dato.media_isActive === 1;



                    if (new Date().toDateString() <= new Date(dato.media_date + 'T00:00:00')
                        .toDateString()) {
                        switchInput.setAttribute('disabled', true);
                    } else {
                        if (new Date().getHours() > parseInt(dato.media_time.split(':')[0])) {
                            switchInput.setAttribute('disabled', true);

                        }
                    }

                    const switchLabel = document.createElement('label');
                    switchLabel.setAttribute('id', `label_check_${dato.media_id}`);
                    switchLabel.classList.add('form-check-label');
                    switchLabel.setAttribute('for', `check_${dato.media_id}`);
                    switchLabel.textContent = dato.media_isActive === 1 ? ' Activo' : ' Inactivo';
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


        function updatePagination(data) {
            totalPages = datos.last_page;
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
