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
                        <div class="form-group">
                            <label for="">Jumlah Sebelumnya</label>
                            <input type="number" min="0" class="form-control" disabled name="jumlah_sebelumnya">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah Saat Ini</label>
                            <input type="number" min="0" class="form-control" disabled name="jumlah_saat_ini">
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
