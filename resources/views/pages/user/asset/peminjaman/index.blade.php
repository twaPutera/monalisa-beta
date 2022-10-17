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
                    guid_peminjam_asset: "{{ $user->guid }}",
                    with: ['request_peminjaman_asset.kategori_asset'],
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
                <a href="#" data-link_detail="${data.link_detail}" data-bs-toggle="modal" data-bs-target="#ModalBasic" class="mb-2 bg-white px-2 py-2 d-block border-radius-sm border border-primary">
                    <p class="text-dark mb-0 asset-deskripsi">${data.request_peminjaman_asset.map((item) => (
                        ' ' + item.kategori_asset.nama_kategori
                    ))}</p>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center" style="width: 60%;">
                            <div class="" style="">
                                <p class="text-primary mb-0 asset-deskripsi"><i>${data.status === 'pending' ? "Menunggu" : data.status === 'dipinjam' ? 'Dipinjam' : data.status === 'duedate' ? "Terlambat" : "Selesai"}</i></p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center" style="width: 40%;">
                            <div class="me-1 text-end">
                                <span class="text-grey text-end">${data.tanggal_peminjaman}</span>
                            </div>
                            <div class="mb-0 text-grey text-end" style="font-size: 20px !important;">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                            </div>
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
<div class="modal fade modalbox" id="ModalBasic" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Peminjaman</h5>
                <a href="#" data-bs-dismiss="modal">Tutup</a>
            </div>
            <div class="modal-body">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Tanggal Peminjaman</strong></label>
                        <input type="text" name="" readonly value="2022/01/01" class="form-control" id="" placeholder="">
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="text-dark" for=""><strong>Tanggal Pengembalian</strong></label>
                        <input type="text" name="" readonly value="2022/01/01" class="form-control" id="" placeholder="">
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="badge badge-success">Sedang Dipinjam</span>
                </div>
                <div class="mt-2 border-radius-sm border p-1">
                    <p class="text-dark"><strong>Kategori Asset 2</strong></p>
                    <ul class="listview simple-listview px-0">
                        <li>Asset 1</li>
                        <li>Asset 2</li>
                        <li>Asset 3</li>
                    </ul>
                </div>
                <div class="mt-2 border-radius-sm border p-1">
                    <p class="text-dark"><strong>Kategori Asset 2</strong></p>
                    <ul class="listview simple-listview">
                        <li>Asset 1</li>
                        <li>Asset 2</li>
                        <li>Asset 3</li>
                    </ul>
                </div>
                <div class="mt-2">
                    <p class="text-dark mb-1"><strong>Alasan Peminjaman</strong></p>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nisi, aperiam! Illum nobis ad sapiente debitis id? Non officia mollitia eum, cupiditate nemo unde, odio aliquam voluptatibus qui magni, fugiat harum?</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection