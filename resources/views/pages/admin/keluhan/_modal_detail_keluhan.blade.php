<div class="modal fade modalDetailKeluhanData" id="modalDetailKeluhanData" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Detail Keluhan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit">
                <div class="modal-body row">
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Tanggal Keluhan Masuk</label>
                            <input type="text" class="form-control" disabled name="tanggal_pengaduan">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Asset</label>
                            <input type="text" class="form-control" disabled name="nama_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Lokasi Asset</label>
                            <input type="text" class="form-control" disabled name="lokasi_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Dilaporkan Oleh</label>
                            <input type="text" class="form-control" disabled name="diajukan_oleh">
                        </div>

                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Kelompok Asset</label>
                            <input type="text" class="form-control" disabled name="kelompok_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Jenis Asset</label>
                            <input type="text" class="form-control" disabled name="jenis_asset">
                        </div>
                        <div class="form-group">
                            <label for="">Catatan Keluhan</label>
                            <textarea cols="30" rows="10" class="form-control" name="catatan_pengaduan" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Saat Ini</label>
                                <div id="status_laporan">

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Catatan Admin</label>
                            <textarea cols="30" rows="10" class="form-control" disabled name="catatan_admin"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>
