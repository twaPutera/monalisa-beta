@extends('layouts.user.master-detail')
@section('page-title', 'Add Service')
@section('pluggin-css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" @endsection
    @section('pluggin-js') <script
    src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}">
</script>
@endsection
    @section('custom-js')
<script>
    $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
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
                if (key == "file_asset_service") {
                    $('#preview-file-error').html(errors[key][0]);
                }
            }
        });

        const generateSelect2KategoriService = () => {
            $('#kategoriServiceCreate').select2({
                width: '100%',
                placeholder: 'Pilih Kategori Service',
                ajax: {
                    url: '{{ route('admin.setting.kategori-service.get-data-select2') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keyword: params.term, // search term
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                        };
                    },
                    cache: true
                },
            });
        }
        generateSelect2KategoriService();
        $('#gambar_asset').on('change', function() {
            const file = $(this)[0].files[0];
            $('#preview-file-text').text(file.name);
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
<form action="{{ route('user.asset-data.service.store', $asset_data->id) }}" class="form-submit" method="POST">
    @csrf
    <div class="section mt-2">
        <h2>{{ $asset_data->deskripsi }}</h2>

        <div class="mt-2">
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Tanggal Service</strong></label>
                    <input type="date" name="tanggal_mulai_service" class="form-control" id="" placeholder="Text Input">
                    <i class="clear-input">
                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                        </ion-icon>
                    </i>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Tanggal Selesai</strong></label>
                    <input type="date" name="tanggal_selesai_service" class="form-control" id=""
                        placeholder="Text Input">
                    <i class="clear-input">
                        <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                        </ion-icon>
                    </i>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Permasalahan</strong></label>
                    <textarea name="permasalahan" class="form-control" id="" cols="30" rows="5"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="input-wrapper">
                    <label for="" class="text-dark"><strong>Kategori Service</strong></label>
                    <select name="id_kategori_service" class="form-control" id="kategoriServiceCreate">

                    </select>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Tindakan</strong></label>
                    <textarea name="tindakan" class="form-control" id="" cols="30" rows="5"></textarea>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Catatan</strong></label>
                    <textarea name="catatan" class="form-control" id="" cols="30" rows="5"></textarea>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Status Servis</strong></label>
                    <select name="status_service" class="form-control mr-3" id="">
                        <option value="onprogress">Proses</option>
                        <option value="backlog">Tertunda</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Status Kondisi Aset</strong></label>
                    <select name="status_kondisi" class="form-control mr-3" id="">
                        <option value="bagus">Bagus</option>
                        <option value="rusak">Rusak</option>

                    </select>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="text-dark" for=""><strong>Gambar Pengaduan</strong></label>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span id="preview-file-text">No File Choosen</span> <br>
                            <span id="preview-file-error" class="text-danger"></span>
                        </div>
                        <label for="gambar_asset" class="btn btn-primary">
                            Upload
                            <input type="file" id="gambar_asset" accept=".jpeg,.png,.jpg,.gif,.svg" class="d-none"
                                name="file_asset_service">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
    @section('button-menu') <div class="d-flex justify-content-center">
    <a href="{{ route('user.asset-data.detail', $asset_data->id) }}" class="btn btn-danger border-radius-sm px-3 me-2">
        <span class="">Batal</span>
    </a>
    <button class="btn btn-success border-radius-sm px-3" onclick="submitForm()" type="button">
        <span class="">Simpan</span>
    </button>
    </div>
@endsection
