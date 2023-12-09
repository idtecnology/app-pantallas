@extends('layouts.app')



@section('content')
    <div class="col-12">
        <table class="table table-bordered table-sm mb-b mt-4">
            <thead class="text-uppercase table-dark">
                <tr>
                    <th>Cliente / Campaña</th>
                    <th>Duracion</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Visto</th>
                    <th>Activar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $dato)
                    <tr class="align-middle">
                        <td>
                            @if ($dato->campania_name)
                                {{ $dato->campania_name }}
                            @else
                                {{ $dato->email }}
                            @endif
                        </td>
                        <td>
                            @if ($dato->media_duration)
                                {{ $dato->media_duration }} segundos
                            @else
                                15 segundos
                            @endif
                        </td>
                        <td>{{ date('d-m-Y', strtotime($dato->media_date)) }}</td>
                        <td>{{ date('H:i', strtotime($dato->media_time)) ?? '' }}</td>
                        <td>{{ $dato->media_reproducido == 1 ? 'Reproducido' : 'No Reproducido' }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input id="check_{{ $dato->media_id }}" onchange="disabledMedia({{ $dato->media_id }});"
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
            </tbody>
        </table>
    </div>
@endsection

@section('js')
    <script>
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
