@extends('layouts.user.master-detail')
@section('page-title', 'Add Service')
@section('custom-js')
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                //
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            //
        });
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.asset-data.detail', $asset_data->id) }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
<div class="section mt-2">
    <h2>{{ $asset_data->deskripsi }}</h2>

    <div class="mt-2">
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="label" for="text4b">Text</label>
                <input type="date" class="form-control" id="text4b" placeholder="Text Input">
                <i class="clear-input">
                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                </i>
            </div>
        </div>
    </div>
</div>
@endsection
@section('button-menu')
    <div class="d-flex justify-content-center">
        <button class="btn btn-danger border-radius-sm px-3 me-2" type="button">
            <span class="">Batal</span>
        </button>
        <button class="btn btn-success border-radius-sm px-3" type="button">
            <span class="">Simpan</span>
        </button>
    </div>
@endsection