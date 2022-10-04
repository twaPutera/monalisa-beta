<div class="modal fade modalCreateInventarisData" id="modalCreateInventarisData" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Inventaris</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.listing-inventaris.store') }}" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="">Kategori Inventaris</label>
                            <div class="input-group mb-2">
                                <select name="id_kategori_inventori" class="form-control selectKategoriData"
                                    id="">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Jenis Inventaris</label>
                            <input type="text" class="form-control" name="kode_inventori">
                        </div>
                        <label for="">Satuan Inventaris</label>
                        <div class="form-group">
                            <div class="input-group mb-2">
                                <select name="id_satuan_inventori" class="form-control selectSatuanData" id="">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Merk Inventaris</label>
                            <input type="text" class="form-control" name="nama_inventori">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah</label>
                            <input type="number" min="0" class="form-control" name="stok">
                        </div>
                        <div class="form-group">
                            <label for="">Harga Beli</label>
                            <input type="number" min="0" class="form-control" name="harga_beli">
                        </div>
                    </div>
                    <div class="col-6 col-md-6">
                        <div class="form-group">
                            <label for="">Deskripsi Inventaris</label>
                            <textarea cols="30" rows="15" class="form-control" name="deskripsi_inventori"></textarea>
                        </div>
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
