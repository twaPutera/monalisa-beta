<div class="modal fade modalEditStokData" id="modalEditStokData" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Pengurangan Stok Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="#" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-12 col-md-4">
                        <div class="pt-3 pb-1" style="border-radius: 9px; background: #E5F3FD;">
                            <table id="tablePropertiEditStok" class="table table-striped">
                                <tr>
                                    <td width="40%">Jenis Inventaris</td>
                                    <td><strong class="kode_inventori"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">Kategori Inventaris</td>
                                    <td><strong class="nama_kategori"></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="40%">Merk Inventaris</td>
                                    <td><strong class="merk_inventaris"></strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Jumlah Inventaris Sebelumnya</td>
                                    <td><strong class="jumlah_sebelumnya"></strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Jumlah Inventaris Saat Ini</td>
                                    <td><strong class="jumlah_saat_ini"></strong></td>
                                </tr>
                                <tr>
                                    <td width="40%">Deskripsi Inventaris</td>
                                    <td><strong class="deskripsi_inventaris"></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="">Nomor Memo</label>
                            <input type="text" class="form-control" name="no_memo">
                        </div>
                        <div class="form-group">
                            <label for="">Tanggal Penggunaan</label>
                            <input type="text" class="form-control datepickerCreate" readonly name="tanggal">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Inventaris Keluar</label>
                            <input type="number" min="0" class="form-control" name="jumlah_keluar">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
