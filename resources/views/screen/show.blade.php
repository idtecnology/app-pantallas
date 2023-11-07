@extends('layouts.app')
@section('content')
    <div class="col-12">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Nombre:</strong>
                    <label for="">{{ $data->name }}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Ubicacion:</strong>
                    <label for="">{{ $data->location }}</label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                <div class="form-group">
                    <strong>Precio:</strong>
                    <label for="">{{ $data->price }}</label>

                </div>
            </div>

        </div>

    </div>
@endsection
