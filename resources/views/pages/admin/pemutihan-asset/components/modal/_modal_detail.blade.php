<div class="modal fade modalDetailInventarisData" id="modalDetailInventarisData" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Detail Pemutihan Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit">
                <div class="modal-body row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="">Tanggal Pemutihan</label>
                            <input type="text" class="form-control" disabled name="tanggal">
                        </div>
                        <div class="form-group">
                            <label for="">No Memo</label>
                            <input type="text" class="form-control" disabled name="no_memo">
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan Pemutihan</label>
                            <textarea cols="30" rows="10" class="form-control" disabled name="keterangan_pemutihan"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Status Pemutihan</label>
                            <input type="text" class="form-control" disabled name="status_pemutihan">
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="">Item Asset Yang Di Putihkan</label>
                            <div class="table-responsive">
                                <table class="table table-striped mb-0 detailAssetData" id="detailAssetData">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Kode Asset</th>
                                            <th>Jenis Asset</th>
                                            <th>Lokasi Asset</th>
                                            <th>Kondisi Asset</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
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
