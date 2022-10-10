@extends('layouts.user.master-detail')
@section('page-title', 'Ajukan Peminjaman')
@section('pluggin-css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
@endsection
@section('custom-css')
    <style>

    </style>
@endsection
@section('pluggin-js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
@endsection
@section('custom-js')
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);

                setTimeout(() => {
                    window.location.href = '{{ route("user.dashboard.index") }}';
                }, 2000);
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            for (let key in errors) {
                let element = formElement.find(`[name=${key}]`);
                clearValidation(element);
                showValidation(element, errors[key][0]);
            }
        });

        $(document).ready(function() {
            generateSelect2GroupKategori();
            generateSelect2Kategori();
        });
    </script>
    <script>
        const generateSelect2GroupKategori = () => {
            $('#kelompokAsset').select2({
                'placeholder': 'Pilih Kelompok Asset',
                'allowClear': true,
                'width': '100%',
                'ajax': {
                    'url': "{{ route('user.api-master.group-kategori-asset.get-data-select2') }}",
                    'dataType': 'json',
                    'data': function(params) {
                        return {
                            'keyword': params.term,
                        }
                    },
                    'processResults': function(response) {
                        return {
                            'results': response.data
                        }
                    }
                }
            })
        }

        const generateSelect2Kategori = () => {
            $('#jenisAsset').select2({
                'placeholder': 'Pilih Asset',
                'allowClear': true,
                'width': '100%',
                'ajax': {
                    'url': "{{ route('user.api-master.kategori-asset.get-data-select2') }}",
                    'dataType': 'json',
                    'data': function(params) {
                        return {
                            'keyword': params.term,
                            'id_group_kategori_asset': $('#kelompokAsset').val()
                        }
                    },
                    'processResults': function(response) {
                        return {
                            'results': response.data
                        }
                    }
                }
            }).on('select2:selecting', function(e) {
                let data = e.params.args.data;
                console.log(data);
                $('#detailPeminjamanContainer').append(generateTemplateDetailPeminjaman(data));
                // $('#idKategoriAsset').val(data.id);
            }).on('select2:unselecting', function(e) {
                let data = e.params.args.data;
                console.log(data);
                $(`#${data.id}`).remove();
            });
        }

        // $('#jenisAsset').on('change', function (event) {
        //     console.log(event);
        //     $('#detailPeminjamanContainer').append(generateTemplateDetailPeminjaman());
        // });

        const generateTemplateDetailPeminjaman = (item) => {
            return `
                <div class="row" id="${item.id}">
                    <div class="col-8">
                        <input type="text" value="${item.text}" readonly name="data_jenis_asset[${item.id}]" class="form-control py-3">
                    </div>
                    <div class="col-4">
                        <input type="number" value="1" name="jumlah[${item.id}]" class="form-control py-3">
                    </div>
                </div>
            `;
        }

        const submitForm = () => {
            $('.form-submit').submit();
        }
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.dashboard.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
<div class="section mt-2">
    <form action="{{ route('user.asset-data.peminjaman.store') }}" method="POST" class="mt-2 form-submit">
        @csrf
        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="text-dark" for="kelompokAsset"><strong>Kelompok Asset</strong></label>
                <select name="" class="form-control py-3" id="kelompokAsset">

                </select>
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="text-dark" for="jenisAsset"><strong>Jenis Asset</strong></label>
                <select name="id_jenis_asset[]" class="form-control py-3" multiple id="jenisAsset">

                </select>
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper p-1 border border-primary border-radius-sm">
                <label class="text-dark" for="jenisAsset"><strong>Detail Peminjaman</strong></label>
                <div id="detailPeminjamanContainer">

                </div>
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="text-dark" for=""><strong>Tanggal Peminjaman</strong></label>
                <input type="date" name="tanggal_peminjaman" class="form-control" id="" placeholder="Text Input">
                <i class="clear-input">
                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                </i>
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="text-dark" for=""><strong>Tanggal Pengembalian</strong></label>
                <input type="date" name="tanggal_pengembalian" class="form-control" id="" placeholder="Text Input">
                <i class="clear-input">
                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                </i>
            </div>
        </div>

        <div class="form-group boxed">
            <div class="input-wrapper">
                <label class="text-dark" for=""><strong>Alasan Peminjaman</strong></label>
                <textarea name="alasan_peminjaman" class="form-control" id="" cols="30" rows="10"></textarea>
            </div>
        </div>
    </form>
</div>
@endsection
@section('button-menu')
    <div class="d-flex justify-content-center">
        <button class="btn btn-danger border-radius-sm px-3 me-2" type="button">
            <span class="">Batal</span>
        </button>
        <button onclick="submitForm()" class="btn btn-success border-radius-sm px-3" type="button">
            <span class="">Simpan</span>
        </button>
    </div>
@endsection