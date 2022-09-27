<div class="modal fade modalCreateDetailInventaris" id="modalCreateDetailInventaris" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.detail-inventaris.store', $inventaris->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Lokasi Inventaris</label>
                        <div class="input-group mb-2">
                            <select name="id_lokasi" class="form-control selectLokasi" id="">

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Stok</label>
                        <input type="number" min="0" class="form-control" name="stok">
                    </div>
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <textarea cols="30" rows="10" class="form-control" name="keterangan"></textarea>
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
