<div class="modal fade modalEditKategoriAsset" id="modalCreate" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Kategori Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.setting.kategori-asset.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Group</label>
                        <select name="id_group_kategori_asset" class="form-control selectGroup" id="selectGroupEdit">

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Kode Kategori</label>
                        <input type="text" class="form-control" name="kode_kategori">
                    </div>
                    <div class="form-group">
                        <label for="">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori">
                    </div>
                    <div class="form-group">
                        <label for="">Umur Asset</label>
                        <input type="number" class="form-control" name="umur_asset">
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
