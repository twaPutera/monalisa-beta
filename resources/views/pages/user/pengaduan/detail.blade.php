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
    <script>
        $(document).ready(function() {
            getAllDataLogPengaduan('logContainer');
        })
    </script>
    <script>
        const getAllDataLogPengaduan = (idContainer, idPengaduan) => {
            $.ajax({
                url: '{{ route('user.pengaduan.get-all-data-log') }}',
                data: {
                    with: ['pengaduan'],
                    id_pengaduan: "{{ $pengaduan->id }}"
                },
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function(index, value) {
                                $('#' + idContainer).append(generateTemplateApproval(value));
                            })
                        } else {
                            $('#' + idContainer).append(`
                                <div class="section text-center mt-2">
                                    <h4 class="text-grey">Tidak Ada Data</h4>
                                </div>
                            `);
                        }
                    }
                }
            })
        }

        const gambarPengaduanSaya = () => {
            $('#gambarPengaduanSaya').submit();
        }
        const generateStatusPeminjaman = (status) => {
            let template = '';
            if (status == 'dilaporkan') {
                template = '<span class="badge badge-warning">Dilaporkan</span>';
            } else if (status == 'diproses') {
                template = '<span class="badge badge-primary">Diproses</span>';
            } else if (status == 'selesai') {
                template = '<span class="badge badge-success">Selesai</span>';
            } 

            return template;

        }

        const generateTemplateApproval = (data) => {
            return `
            <a href="#" class="mb-2 bg-white px-2 py-2 d-block border-radius-sm border border-primary">
                <div class="py-2 px-2">
                    <div class="border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-dark mb-0"><strong>Tanggal Perpanjangan</strong></p>
                            <p class="mb-0">${data.tanggal_log}</p>
                        </div>
                        ${generateStatusPeminjaman(data.status)}
                    </div>
                    <p class="mb-0">${data.message_log}</p>
                </div>
            </a>
            
        `;
        }
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.pengaduan.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <ul class="nav nav-tabs lined" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#overview2" role="tab" aria-selected="true">
                Detail Aduan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#cards3" role="tab" aria-selected="false">
                Log Pengaduan
            </a>
        </li>
    </ul>
    <div class="tab-content mt-2">
        <div class="tab-pane fade active show" id="overview2" role="tabpanel">
            <div class="section">
                <div class="mt-2 mb-2">
                    <div class="py-2 border-bottom border-secondary">
                        <div class="row">
                            <div class="col">
                                <p class="mb-0 text-green">Nama Asset</p>
                            </div>
                            <div class="col">
                                <p class="mb-0 text-green text-end">
                                    {{ $pengaduan->asset_data != null ? $pengaduan->asset_data->deskripsi : 'Laporan Pengaduan' }}
                                </p>
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
                                    {{ $pengaduan->asset_data != null ? $pengaduan->asset_data->lokasi->nama_lokasi : $pengaduan->lokasi->nama_lokasi }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @if ($pengaduan->asset_data != null)
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
                                        {{ $pengaduan->asset_data->kategori_asset->nama_kategori ?? 'Tidak Ada Kategori' }}
                                    </p>
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
                    @endif

                    <div class="py-2 border-bottom border-secondary">
                        <div class="row">
                            <div class="col">
                                <p class="mb-0 text-green">Tanggal Pengaduan</p>
                            </div>
                            <div class="col">
                                <p class="mb-0 text-green text-end">
                                    {{ App\Helpers\DateIndoHelpers::formatDateToIndo($pengaduan->tanggal_pengaduan) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 border-bottom border-secondary">
                        <div class="row">
                            <div class="col">
                                <p class="mb-0 text-green">Prioritas</p>
                            </div>
                            @php
                                if ($pengaduan->prioritas == 10) {
                                    $prioritas = '<span class="badge badge-danger px-3">High</span>';
                                } elseif ($pengaduan->prioritas == 5) {
                                    $prioritas = '<span class="badge badge-warning px-3">Medium</span>';
                                } elseif ($pengaduan->prioritas == 1) {
                                    $prioritas = '<span class="badge badge-info px-3">Low</span>';
                                } else {
                                    $prioritas = '<span class="badge badge-secondary px-3">Tidak Ada</span>';
                                }
                            @endphp
                            <div class="col text-end">
                                {!! $prioritas !!}
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
                    @if (isset($pengaduan->image[0]))
                        <div class="py-2 border-bottom border-secondary">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-0 text-green">Gambar Pengaduan Saya</p>
                                </div>
                                <div class="col  text-end">
                                    <a href="{{ route('user.pengaduan.download-gambar') . '?filename=' . $pengaduan->image[0]->path . '&status=request' }}"
                                        download class="btn btn-primary shadow-customD btn-sm mb-0"><i
                                            class="fa fa-download"></i>
                                        Unduh
                                        Gambar</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($pengaduan->status_pengaduan != 'dilaporkan')
                        <div class="py-2 border-bottom border-secondary">
                            <div class="row">
                                <div class="col">
                                    <p class="mb-0 text-green">Catatan Respon Pengaduan</p>
                                </div>
                                <div class="col">
                                    <p class="mb-0 text-green text-end">{{ $pengaduan->catatan_admin ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        @if (isset($pengaduan->image[1]))
                            <div class="py-2 border-bottom border-secondary">
                                <div class="row">
                                    <div class="col">
                                        <p class="mb-0 text-green">Gambar Respon Pengaduan</p>
                                    </div>
                                    <div class="col  text-end">
                                        <a href="{{ route('user.pengaduan.download-gambar') . '?filename=' . $pengaduan->image[1]->path . '&status=response' }}"
                                            download class="btn btn-primary shadow-customD btn-sm mb-0"><i
                                                class="fa fa-download"></i>
                                            Unduh
                                            Gambar</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="cards3" role="tabpanel">
            <div class="section">
                <div class="" id="logContainer"></div>
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
