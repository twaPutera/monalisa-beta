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
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);

                setTimeout(() => {
                    window.location.href = '{{ route('user.asset-data.detail', $asset_data->id) }}';
                }, 2000);
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            if (!errors.success) {
                changeTextToast('toastDanger', errors.message);
                toastbox('toastDanger', 2000)
            }
            for (let key in errors) {
                let array_error_key = key.split('.');
                if (array_error_key.length < 2) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                } else {
                    let new_key = `${array_error_key[0]}[${array_error_key[1]}][${array_error_key[2]}]`;
                    let element = formElement.find(`[name="${new_key}"]`);
                    $(element).addClass('is-invalid');
                    $(`#errorJumlah-${array_error_key[1]}`).text(errors[key][0]).show();
                }
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

        const submitForm = () => {
            $('.form-submit').submit();
        }
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.asset-data.detail', $asset_data->id) }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <form action="{{ route('user.asset-data.opname.store', $asset_data->id) }}" class="form-submit
        " method="POST">
        @csrf
        <div class="section mt-2">
            <h2>{{ $asset_data->deskripsi }}</h2>

            <div class="mt-2">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Tanggal Opname</strong></label>
                        <input type="date" name="tanggal_opname" class="form-control" id=""
                            placeholder="Text Input">
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                            </ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Tanggal Perencanaan Servis</strong></label>
                        <input type="date" name="tanggal_services" class="form-control" id=""
                            placeholder="Text Input">
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                            </ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span>Status Kondisi Terakhir </span> <br>
                        </div>
                        @if ($asset_data->status_kondisi == 'bagus')
                            <div class="badge badge-success">Bagus</div>
                        @elseif($asset_data->status_kondisi == 'rusak')
                            <div class="badge badge-danger">Rusak</div>
                        @elseif($asset_data->status_kondisi == 'maintenance')
                            <div class="badge badge-info">Maintenance</div>
                        @else
                            <div class="badge badge-warning">Tidak Lengkap</div>
                        @endif
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Status Kondisi Aset</strong></label>
                        <select name="status_kondisi" class="form-control mr-3" id="">
                            <option value="bagus">Bagus</option>
                            <option value="rusak">Rusak</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="tidak-lengkap">Tidak Lengkap</option>
                        </select>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Status Akunting Aset</strong></label>
                        <select name="status_akunting" class="form-control mr-3" id="">
                            @foreach ($list_status as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Gambar Asset Terbaru</strong></label>
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
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Catatan</strong></label>
                        <textarea name="catatan" class="form-control" id="" cols="30" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('button-menu')
    <div class="d-flex justify-content-center">
        <a href="{{ route('user.asset-data.detail', $asset_data->id) }}" class="btn btn-danger border-radius-sm px-3 me-2">
            <span class="">Batal</span>
        </a>
        <button class="btn btn-success border-radius-sm px-3" onclick="submitForm()" type="submit">
            <span class="">Simpan</span>
        </button>
    </div>
@endsection
