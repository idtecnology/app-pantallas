<div class="col-md-6 col-sm-12 my-3">
    <div class="d-flex">
        <img class="rounded-start-4 img-fluid w-50" src="{{ $img ?? 'https://placehold.co/148x106' }}" alt="">
        <div class="ms-3">
            <div>
                <span class="font-bold fs-3">{{ $title ?? 'Title POS' }}</span>
                <div class="clear-fix"></div>
                <span>{{ $address ?? 'addess POS' }}</span>
            </div>

            <a href="{{ route('pantalla1', 1) }}" class="btn btn-primary rounded-pill p-1 px-2 mt-3 btn-block">Publica
                ahora</a>
        </div>
    </div>
</div>
