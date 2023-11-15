<div class="col-6 my-3">
    <div class="d-flex rounded-4">
        <img class="rounded-start-4" src="{{ $img ?? 'https://placehold.co/300x169' }}" alt="">
        <div class=" d-flex flex-column justify-content-around ms-3 w-100 p-3">
            <div>
                <h2 class="font-bold">{{ $title ?? 'Title POS' }}</h2>
                <span>{{ $address ?? 'addess POS' }}</span>
            </div>

            <a href="{{ route('pantalla1', 1) }}" class="btn btn-primary rounded-5 btn-block">Publica ahora</a>
        </div>
    </div>
</div>
