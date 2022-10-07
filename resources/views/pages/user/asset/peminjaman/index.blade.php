@extends('layouts.user.master')
@section('page-title', 'Daftar Peminjaman')
@section('custom-js')
    <script>
        $(document).ready(function() {
            getAllDataPeminjaman('pendingContainer', 'pending');
            getAllDataPeminjaman('dipinjamContainer', 'dipinjam');
            getAllDataPeminjaman('selesaiContainer', 'selesai');
        })
    </script>
    <script>
        const getAllDataPeminjaman = (idContainer, status) => {
            $.ajax({
                url: '{{ route("user.asset-data.peminjaman.get-all-data") }}',
                data: {
                    guid_penerima_asset: "{{ $user->guid }}",
                    with: ['detail_peminjaman_asset'],
                    status: status
                },
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        if (response.data.length > 0) {
                            $(response.data).each(function (index, value) {
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

        const generateTemplateApproval = (data) => {
            return `
                <a href="${data.link_detail}" class="mb-2 bg-white px-2 py-2 d-flex justify-content-between border-radius-sm border border-primary">
                    <div class="d-flex align-items-center" style="width: 60%;">
                        <div class="ms-1" style="">
                            <p class="text-dark mb-0 asset-deskripsi">${data.asset_data.deskripsi}</p>
                            <p class="text-primary mb-0 asset-deskripsi"><i>${data.asset_data.kategori_asset ? data.asset_data.kategori_asset.group_kategori_asset.nama_group : 'Not Found'}, ${data.asset_data.kategori_asset ? data.asset_data.kategori_asset.nama_kategori : "Not Found"}</i></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center" style="width: 40%;">
                        <div class="me-1 text-end">
                            <p class="text-grey mb-0 text-end">${data.status === 'pending' ? "Menunggu" : data.status ? 'dipinjam' === "Due Date" : "Selesai"}</p>
                            <span class="text-grey text-end">${data.tanggal_peminjaman}</span>
                        </div>
                        <div class="mb-0 text-grey text-end" style="font-size: 20px !important;">
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </div>
                    </div>
                </a>
            `;
        }
    </script>
@endsection
@section('content')
<ul class="nav nav-tabs lined" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#overview2" role="tab" aria-selected="true">
            Menunggu
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#cards2" role="tab" aria-selected="false">
            Sedang Dipinjam
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#cards3" role="tab" aria-selected="false">
            Selesai Dipinjam
        </a>
    </li>
</ul>
<div class="tab-content mt-2">
    <div class="tab-pane fade active show" id="overview2" role="tabpanel">
        <div class="section">
            <div class="" id="pendingContainer"></div>
        </div>
    </div>
    <div class="tab-pane fade" id="cards2" role="tabpanel">
        <div class="section">
            <div class="" id="dipinjamContainer"></div>
        </div>
    </div>
    <div class="tab-pane fade" id="cards3" role="tabpanel">
        <div class="section">
            <div class="" id="selesaiContainer"></div>
        </div>
    </div>
</div>
@endsection