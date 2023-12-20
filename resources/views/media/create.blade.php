@extends('layouts.app')
@section('content')
    <div class="col-12">
        {!! Form::open([
            'route' => 'sale.store-massive',
            'method' => 'POST',
            'enctype' => 'multipart/form-data',
            'id' => 'miFormulario',
        ]) !!}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="col-12 mb-3">
                    <div class="form-group">
                        <strong>Nombre campaña:</strong>
                        {!! Form::text('name', null, ['placeholder' => 'Nombre', 'class' => 'form-control', 'id' => 'name']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <strong>Punto de venta:</strong>
                    <select class='form-select' name="screen_id" id="screen_id">
                        @foreach ($pos as $p)
                            <option value="{{ $p->_id }}">{{ $p->nombre }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="form-group">
                    <strong>Fecha inicio:</strong>
                    <input type="date" min='{{ date('Y-m-d') }}' id="fecha_inicio" name='fecha_inicio'
                        class="form-control">
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="form-group">
                    <strong>Fecha fin:</strong>
                    <input type="date" min='{{ date('Y-m-d') }}' id="fecha_fin" name='fecha_fin' class="form-control">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-6 col-md-4 mb-3">
                <div class="form-group">
                    <strong>Hora inicio:</strong>
                    <input type="time" name='hora_inicio' id="hora_inicio" class="form-control">
                </div>
            </div>
            <div class="col-6 col-md-4 mb-3">
                <div class="form-group">
                    <strong>Hora fin:</strong>
                    <input type="time" name='hora_fin' id="hora_fin" class="form-control">
                </div>
            </div>


            <div class="col-12 col-md-4 mb-3">
                <div class="form-group">
                    <strong>Cantidad por hora:</strong>
                    <input type="text" class="form-control" name="cant" id="cant">
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="form-group">
                    <strong>Multimedia:</strong>
                    <input type="file" required id="files" name='files[]' multiple class="form-control">

                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('js')
    <script>
        const csrfToken = "{{ csrf_token() }}";
        document.getElementById('miFormulario').addEventListener('submit', function(event) {
            event.preventDefault();
            // Realiza la validación aquí
            const cantInput = document.getElementById('cant');
            const nameInput = document.getElementById('name');
            const screenIdInput = document.getElementById('screen_id');
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaFinInput = document.getElementById('fecha_fin');
            const horaInicioInput = document.getElementById('hora_inicio');
            const horaFinInput = document.getElementById('hora_fin');
            const filesInput = document.getElementById('files');

            if (nameInput.value == '') {
                Swal.fire('Error', 'Por favor, Ingresa un nombre para la campaña.', 'error');
                nameInput.focus()
                return;
            }
            if (screenIdInput.value == '') {
                Swal.fire('Error', 'Por favor, Selecciona un punto de venta.', 'error');
                screenIdInput.focus()
                return;
            }
            if (fechaInicioInput.value == '') {
                Swal.fire('Error', 'Por favor, Ingresa una fecha de inicio.', 'error');
                fechaInicioInput.focus()
                return;
            }
            if (fechaFinInput.value == '') {
                Swal.fire('Error', 'Por favor, Ingresa una fecha de fin.', 'error');
                fechaFinInput.focus()
                return;
            }
            if (horaInicioInput.value == '') {
                Swal.fire('Error', 'Por favor, Ingresa una hora de inicio.', 'error');
                horaInicioInput.focus()
                return;
            }
            if (horaFinInput.value == '') {
                Swal.fire('Error', 'Por favor, Ingresa una hora de fin.', 'error');
                horaFinInput.focus()
                return;
            }

            if (fechaInicioInput.value > fechaFinInput.value) {
                Swal.fire('Error', 'La fecha de inicio debe ser mayor a la fecha de finalizacion.', 'error');
                fechaFinInput.focus()
                return;
            }
            if (cantInput.value == '') {
                Swal.fire('Error', 'Por favor, completa la cantidad correctamente.', 'error');
                cantInput.focus()
                return;
            }

            const formData = new FormData(this);

            Swal.showLoading();

            fetch("{{ route('sale.store-massive') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.hideLoading()
                    if (data.status == 'success') {
                        Swal.fire('Carga masiva', data.message, 'success')
                        location.reload();
                    } else {
                        Swal.fire('Carga masiva', data.message, 'error')
                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        document.getElementById('cant').addEventListener('keyup', function(event) {
            const inputValue = this.value;
            this.value = inputValue.replace(/\D/g, '');
        });
    </script>
@endsection
