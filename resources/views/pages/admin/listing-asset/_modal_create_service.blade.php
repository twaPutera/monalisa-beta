<div class="modal fade modalCreateAssetService" id="modalCreateAssetService" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-asset.service-asset.store', $asset->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Deskripsi Service</label>
                        <input type="text" class="form-control" name="deskripsi_service">
                    </div>

                    <div class="form-group">
                        <label for="">Tanggal Mulai Service</label>
                        <input type="text" class="form-control datepickerCreate" readonly
                            name="tanggal_mulai_service">
                    </div>

                    <div class="form-group">
                        <label for="">Kategori Service</label>
                        <select name="id_kategori_service" class="form-control" id="kategoriServiceCreate">

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Kondisi Asset Sebelum Servis</label>
                        <textarea name="kondisi_sebelum" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
