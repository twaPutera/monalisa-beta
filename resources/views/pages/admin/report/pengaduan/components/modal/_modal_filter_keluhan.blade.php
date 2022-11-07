<div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Filter History Pengaduan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-md-6 col-6">
                    <label for="">Lokasi Asset</label>
                    <select name="" class="form-control" id="lokasiAssetCreateService">

                    </select>
                </div>
                <div class="form-group col-md-6 col-6">
                    <label for="">Jenis Asset</label>
                    <select name="" class="form-control" id="listKategoriAssetLocation">

                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Awal</label>
                    <input type="text" name="tanggal_awal" readonly class="form-control datepickerAwal mx-2"
                        placeholder="Tanggal Awal">
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Tanggal Akhir</label>
                    <input type="text" name="tanggal_akhir" readonly class="form-control datepickerAkhir mr-2"
                        placeholder="Tanggal Akhir">
                </div>
                <div class="form-group col-md-6 col-6">
                    <label for="">Status Pengaduan</label>
                    <select name="" class="form-control" id="statusPengaduan">
                        <option value="all" selected>Semua Pengaduan</option>
                        <option value="dilaporkan">Laporan Masuk</option>
                        <option value="diproses">Proses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-6">
                    <label for="">Asset Data</label>
                    <select name="" class="form-control" id="assetDataService">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" onclick="filterTableService()" data-dismiss="modal"
                    class="btn btn-primary">Filter</button>
            </div>
        </div>
    </div>
</div>
