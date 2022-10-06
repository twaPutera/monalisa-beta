@extends('layouts.user.master-detail')
@section('page-title', 'Add Opname')
@section('pluggin-css')
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('pluggin-js')
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('custom-js')
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            console.log(data);
            if (data.success) {
                $('#preview-file-error').html('');

            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            console.log(errors);
            for (let key in errors) {
                let element = formElement.find(`[name=${key}]`);
                clearValidation(element);
                showValidation(element, errors[key][0]);
                if (key == "gambar_asset") {
                    $('#preview-file-error').html(errors[key][0]);
                }
            }
        });
        $('#gambar_asset').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
        });
        $('.datepickerCreate').datepicker({
            todayHighlight: true,
            width: '100%',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.asset-data.detail', $asset_data->id) }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('isFormStart')
    <form action="{{ route('user.asset-data.opname.store', $asset_data->id) }}" method="POST">
        @csrf
    @endsection
    @section('content')
        <div class="section mt-2">
            <h2>{{ $asset_data->deskripsi }}</h2>

            <div class="mt-2">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="text4b">Tanggal Opname</label>
                        <input type="text" class="form-control datepickerCreate" readonly name="tanggal_opname">
                    </div>
                </div>
                <div class="form-group boxed">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span>Status Terakhir </span> <br>
                        </div>
                        @if ($asset_data->status_kondisi == 'bagus')
                            <button disabled class="btn btn-success">Bagus</button>
                        @elseif($asset_data->status_kondisi == 'rusak')
                            <button disabled class="btn btn-danger">Rusak</button>
                        @elseif($asset_data->status_kondisi == 'maintenance')
                            <button disabled class="btn btn-info">Maintenance</button>
                        @else
                            <button disabled class="btn btn-warning">Tidak Lengkap</button>
                        @endif
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="">Status Kondisi Aset</label>
                        <select name="status_kondisi" class="form-control mr-3" id="">
                            <option value="bagus">Bagus</option>
                            <option value="rusak">Rusak</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="tidak-lengkap">Tidak Lengkap</option>
                        </select>
                    </div>
                </div>
                {{-- <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="">Status Akunting Aset</label>
                        <select name="status_akunting" class="form-control mr-3" id="">
                            @foreach ($list_status as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="form-group boxed">

                    <label class="label" for="">Gambar Hasil Service</label>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span id="preview-file-text">No File Choosen</span> <br>
                            <span id="preview-file-error" class="text-danger"></span>
                        </div>
                        <label for="gambar_asset" class="btn btn-primary">
                            Upload
                            <input type="file" id="gambar_asset" accept=".jpeg,.png,.jpg,.gif,.svg" class="d-none"
                                name="gambar_asset">
                        </label>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="">Catatan</label>
                        <textarea name="catatan" class="form-control" id="" cols="30" rows="5"></textarea>
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
            <button class="btn btn-success border-radius-sm px-3" type="submit">
                <span class="">Simpan</span>
            </button>
        </div>
    @endsection
    @section('isFormEnd')
    </form>
@endsection
