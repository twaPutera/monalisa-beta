<div class="modal fade modalEditKategoriAsset" id="modalCreate" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Jenis Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.setting.kategori-asset.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Kelompok Asset</label>
                        <select name="id_group_kategori_asset" class="form-control selectGroup" id="selectGroupEdit">

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Kode Jenis Asset</label>
                        <input type="text" class="form-control" name="kode_kategori">
                    </div>
                    <div class="form-group">
                        <label for="">Nama Jenis Asset</label>
                        <input type="text" class="form-control" name="nama_kategori">
                    </div>
                    <div class="form-group">
                        <label for="">Masa Manfaat Komersial</label>
                        <select name="umur_asset" id="selectUmurAssetEdit" class="form-control selectUmurAsset">
                            <option value="15">15 Bulan</option>
                            <option value="14">14 Bulan</option>
                            <option value="13">13 Bulan</option>
                            <option value="12">12 Bulan</option>
                            <option value="11">11 Bulan</option>
                            <option value="10">10 Bulan</option>
                            <option value="9">9 Bulan</option>
                            <option value="8">8 Bulan</option>
                            <option value="7">7 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="5">5 Bulan</option>
                            <option value="4">4 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="2">2 Bulan</option>
                            <option value="1">1 Bulan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
