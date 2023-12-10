@extends('layouts.app')



@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <select class='form-select' name="screen_id" id="screen_id" onchange='selectScreen(this.value)'>
                    @if ($id == 0)
                        <option value="0">Seleccione</option>
                    @else
                        <option value="{{ $scren->_id }}">{{ $scren->nombre }}</option>
                        <option value="0">Seleccione</option>
                    @endif

                    @foreach ($pos as $p)
                        <option value="{{ $p->_id }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-12">
        <table class="table table-bordered table-sm mb-b mt-4">
            <thead class="text-uppercase table-dark">
                <tr>
                    <th>Hora</th>
                    <th>Pantalla</th>
                    <th>Campaña / Cliente</th>
                    <th>Reproducido</th>
                    <th>Activar / Desactivar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($arr3 as $time)
                    <tr class='align-middle text-center'>
                        <td rowspan="{{ count($arr1[$time]) + 1 }}">{{ $time }}</td>
                    </tr>

                    @foreach ($arr1[$time] as $dato)
                        <tr class='align-middle text-center'>
                            <td>{{ $dato->screen_name }}</td>
                            <td>
                                @if ($dato->campania_name)
                                    {{ $dato->campania_name }}
                                @else
                                    {{ $dato->email }}
                                @endif
                            </td>
                            <td>{{ $dato->media_reproducido == 1 ? 'Reproducido' : 'No Reproducido' }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input id="check_{{ $dato->media_id }}"
                                        onchange="disabledMedia({{ $dato->media_id }});"
                                        @if ($dato->media_isActive == 1) checked @endif class="form-check-input"
                                        @if (date('H:i') >= $dato->media_time) disabled @endif type="checkbox" role="switch">
                                    <label id="label_check_{{ $dato->media_id }}" class="form-check-label"
                                        for="flexSwitchCheckDefault">
                                        @if ($dato->media_isActive == 1)
                                            Activo
                                        @else
                                            Inactivo
                                        @endif
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

            </tbody>
        </table>
        <div class="text-center">
            {!! $data->render() !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        function selectScreen(id) {
            console.log(id)

            var urlConParametro =
                `/grilla/${id}`
            window.location.href = urlConParametro;
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

        }
    </script>
@endsection
