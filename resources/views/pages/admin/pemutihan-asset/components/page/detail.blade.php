@extends('layouts.admin.main.master')
@section('plugin_css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/general/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endsection
@section('plugin_js')
    <script src="{{ asset('assets/vendors/general/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('custom_js')
    <script src="{{ asset('assets/vendors/custom/datatables/datatables.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
                if (data.success) {
                    showToastSuccess('Sukses', data.message);
                    if (data.redirect && data.redirect != null) {
                        setTimeout(function() {
                            var redirect = "{{ route('admin.pemutihan-asset.index') }}"
                            location.assign(redirect);
                        }, 1000);
                    }
                }

            });
            $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
                //if validation not pass
                if (!errors.success) {
                    showToastError('Gagal', errors.message);
                }
                for (let key in errors) {
                    let element = formElement.find(`[name=${key}]`);
                    clearValidation(element);
                    showValidation(element, errors[key][0]);
                }
            });
        });

        const submitForm = () => {
            Swal.fire({
                title: 'Apakah anda yakin membatalkan?',
                text: "Data yang batal ditambahkan akan terhapus permanen",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#CF4343',
                cancelButtonColor: '#d5d5d5',
                confirmButtonText: 'Ya, Batalkan !'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-submit').submit();
                }
            })
        }
    </script>
@endsection
@section('main-content')
    <form action="{{ route('admin.pemutihan-asset.store.detail.update', $pemutihan_asset->id) }}"
        class="kt-form kt-form--fit kt-form--label-right form-submit" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4 col-12">
                <h5 class="mb-3"><strong class="text-primary">Pemutihan Asset</strong> <span class="text-gray"> -
                        Tambah Data Pemutihan Asset</span></h5>
                <div class="pt-3 pb-1" style="border-radius: 9px; background: #E5F3FD;">
                    <table id="tableProperti" class="table table-striped">
                        <tr>
                            <td width="40%">Tanggal Pemutihan</td>
                            <td><strong>{{ $pemutihan_asset->tanggal }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">No Berita Acara</td>
                            <td><strong>{{ $pemutihan_asset->no_memo }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">Keterangan Umum</td>
                            <td><strong>{{ $pemutihan_asset->keterangan }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">Status Pemutihan</td>
                            <td>
                                <select name="status_pemutihan" class="form-control">
                                    <option value="Draft" {{ $pemutihan_asset->status == 'Draft' ?? 'selected' }}>Draft
                                    </option>
                                    <option value="Publish" {{ $pemutihan_asset->status == 'Publish' ?? 'selected' }}>
                                        Publish
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">Dibuat Oleh</td>
                            <td><strong>{{ $pemutihan_asset->created_by_name }}</strong></td>
                        </tr>
                        <tr>
                            <td width="40%">File Berita Acara</td>
                            <td>
                                <a href="{{ route('admin.pemutihan-asset.store.detail.download') . '?filename=' . $pemutihan_asset->file_bast }}"
                                    download class="btn btn-primary shadow-custom btn-sm"><i class="fa fa-download"></i>
                                    Unduh</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-8 col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p>Kesalahan terdeteksi:</p>
                        <ol>
                            @foreach ($errors->all() as $index => $item)
                                <li> {{ ucFirst($item) }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif
                <div class="kt-portlet shadow-custom">
                    <div class="kt-portlet__head px-4">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Daftar Asset Diputihkan <span
                                    class="text-primary"><b>({{ $pemutihan_asset->detail_pemutihan_asset->count() }}
                                        Asset)</b></span>
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="table-responsive">
                            <table class="table table-striped dt_table" id="datatableExample">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        {{-- <th width="100px">#</th> --}}
                                        <th>Kode Asset</th>
                                        <th>Jenis Asset</th>
                                        <th>Lokasi Asset</th>
                                        <th>Keterangan Pemutihan</th>
                                        <th>Foto Asset Terbaru</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pemutihan_asset->detail_pemutihan_asset as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->asset_data->kode_asset }}</td>
                                            <td>{{ empty($item->asset_data->kategori_asset->nama_kategori) ? 'Tidak Ada' : $item->asset_data->kategori_asset->nama_kategori }}
                                            </td>
                                            <td>{{ empty($item->asset_data->lokasi->nama_lokasi) ? 'Tidak Ada' : $item->asset_data->lokasi->nama_lokasi }}
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="hidden" name="id_asset[]" value="{{ $item->id }}">
                                                    <textarea cols="15" rows="5" class="form-control" name="keterangan_pemutihan_asset[]"></textarea>
                                                </div>
                                            </td>
                                            <td width="220px">
                                                <div class="form-group">
                                                    <input type="file" name="gambar_asset[]"
                                                        accept=".jpeg,.png,.jpg,.gif,.svg" id="gambar_asset"
                                                        class="form-control">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right mt-2">
                            <button type="button" class="btn btn-secondary" onclick="submitForm()">Batalkan</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{-- Cancel Button, Auto Delete --}}
    <form action="{{ route('admin.pemutihan-asset.store.detail.cancel', $pemutihan_asset->id) }}" method="POST"
        id="form-submit">
        @csrf
    </form>
@endsection
