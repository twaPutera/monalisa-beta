<div class="modal fade" id="modalDetailPeminjaman" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Detail Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <h5>Deskripsi Peminjaman</h5>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Peminjam</label>
                            <input type="text" class="form-control" id="namaPeminjam" readonly name="nama" placeholder="Nama" value="">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Peminjaman</label>
                            <input type="date" class="form-control" id="tanggalPeminjam" readonly name="kode_satuan">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" id="tanggalPengembalian" readonly name="nama_satuan">
                        </div>
                        <div class="form-group">
                            <label for="">Alasan Peminjaman</label>
                            <textarea name="" class="form-control" readonly id="alasanPeminjaman" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <h5>Detail barang yang dipinjam</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50px">No</th>
                                        <th>Nama Kategori</th>
                                        <th width="50px">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyDetailPeminjaman">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-2">
                            <h5>Status Approval Peminjaman</h5>
                        </div>
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" class="form-control custom-select" id="">
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" class="form-control" id="" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>
