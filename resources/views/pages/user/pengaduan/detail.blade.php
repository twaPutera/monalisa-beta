@extends('layouts.user.master-detail')
@section('page-title', 'Detail Aduan Asset')
@section('custom-css')
    <link href="{{ asset('assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection
@section('custom-js')
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                changeTextToast('toastSuccess', data.message);
                toastbox('toastSuccess', 2000);

                setTimeout(() => {
                    window.location.href = '{{ route('user.pengaduan.index') }}';
                }, 2000);
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            if (!errors.success) {
                changeTextToast('toastDanger', errors.message);
                toastbox('toastDanger', 2000)
            }
            for (let key in errors) {
                let element = formElement.find(`[name=${key}]`);
                clearValidation(element);
                showValidation(element, errors[key][0]);

            }
        });
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.dashboard.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <div class="section mt-2">
        <h2 style="color: #6F6F6F;">{{ $pengaduan->asset_data->deskripsi }} </h2>
        <div class="mt-2">
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Kelompok Asset</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            {{ $pengaduan->asset_data->kategori_asset->group_kategori_asset->nama_group ?? 'Tidak Ada Group' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Jenis Asset</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            {{ $pengaduan->asset_data->kategori_asset->nama_kategori ?? 'Tidak Ada Kategori' }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Lokasi</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            {{ $pengaduan->asset_data->lokasi->nama_lokasi ?? 'Tidak Ada Lokasi' }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Status Terakhir</p>
                    </div>
                    @php
                        if ($pengaduan->asset_data->status_kondisi == 'bagus') {
                            $kondisi = '<span class="badge badge-success px-3">Baik</span>';
                        } elseif ($pengaduan->asset_data->status_kondisi == 'rusak') {
                            $kondisi = '<span class="badge badge-danger px-3">Rusak</span>';
                        } elseif ($pengaduan->asset_data->status_kondisi == 'maintenance') {
                            $kondisi = '<span class="badge badge-warning px-3">Maintenance</span>';
                        } elseif ($pengaduan->asset_data->status_kondisi == 'tidak-lengkap') {
                            $kondisi = '<span class="badge badge-info px-3">Tidak Lengkap</span>';
                        }
                    @endphp
                    <div class="col text-end">
                        {!! $kondisi !!}
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Status Pemutihan</p>
                    </div>
                    <div class="col text-end">
                        @php
                            if ($pengaduan->asset_data->is_pemutihan == 0) {
                                $pemutihan = '<span class="badge badge-success px-3">Aktif</span>';
                            } elseif ($pengaduan->asset_data->is_pemutihan == 1) {
                                $pemutihan = '<span class="badge badge-danger px-3">Diputihkan</span>';
                            }
                        @endphp
                        {!! $pemutihan !!}

                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Tanggal Pengaduan</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">
                            {{ App\Helpers\DateIndoHelpers::formatDateToIndo($pengaduan->tanggal_pengaduan) }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Status Pengaduan</p>
                    </div>
                    <div class="col text-end">
                        <span class="badge badge-success px-3">{{ ucWords($pengaduan->status_pengaduan) }}</span>
                        {{-- <p class="mb-0 text-green text-end">Baik</p> --}}
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Catatan Pengaduan</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">{{ $pengaduan->catatan_pengaduan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Catatan Admin</p>
                    </div>
                    <div class="col">
                        <p class="mb-0 text-green text-end">{{ $pengaduan->catatan_admin ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="py-2 border-bottom border-secondary">
                <div class="row">
                    <div class="col">
                        <p class="mb-0 text-green">Gambar Pengaduan</p>
                    </div>
                    <div class="col  text-end">
                        <a href="{{ route('user.pengaduan.download-gambar') . '?filename=' . $pengaduan->image[0]->path }}"
                            download class="btn btn-primary shadow-customD btn-sm mb-0"><i class="fa fa-download"></i> Unduh
                            Gambar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('button-menu')
    @if ($pengaduan->status_pengaduan == 'dilaporkan')
        <form action="{{ route('user.pengaduan.destroy', $pengaduan->id) }}" class="form-submit" method="post">
            @csrf
            <button type="submit" class="btn btn-danger border-radius-sm px-3 me-2">
                <ion-icon name="trash"></ion-icon>
                <span class="">Hapus</span>
            </button>
        </form>
        <a class="btn btn-success border-radius-sm px-3" href="{{ route('user.pengaduan.edit', $pengaduan->id) }}">
            <ion-icon name="pencil"></ion-icon>
            <span class="">Ubah</span>
        </a>
    @else
        @include('layouts.user.bottom-menu')
    @endif
@endsection
