<div class="modal fade modalCreateAsset" id="modalCreate" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Tambah Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="{{ route('admin.listing-asset.store') }}" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="form-group col-md-4 col-6">
                        <label for="">Deskripsi / Nama</label>
                        <input type="text" class="form-control" name="deskripsi">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Group Asset</label>
                        <select name="id_group_asset" class="form-control" id="groupAssetCreate">

                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Kategori Asset</label>
                        <select name="id_kategori_asset" class="form-control" id="kategoriAssetCreate">

                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Kode Asset</label>
                        <input type="text" class="form-control" name="kode_asset">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Tanggal Perolehan</label>
                        <input type="text" class="form-control datepickerCreate" readonly name="tanggal_perolehan">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Nilai Perolehan (Rp)</label>
                        <input type="number" class="form-control" name="nilai_perolehan">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Jenis Perolehan</label>
                        <select class="form-control" name="jenis_penerimaan">
                            <option selected="">Pilih Jenis Perolehan</option>
                            <option value="PO">PO</option>
                            <option value="Hibah">Hibah</option>
                            <option value="Pengadaan">Pengadaan</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Lokasi Asset</label>
                        <select name="id_lokasi" class="form-control" id="lokasiAssetCreate">

                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Ownership / Dipindahkan Ke</label>
                        <input type="text" class="form-control" name="ownership">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Satuan</label>
                        <select name="id_satuan_asset" class="form-control" id="satuanAssetCreate">

                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Vendor</label>
                        <select name="id_vendor" class="form-control" id="vendorAssetCreate">

                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Nomor Seri</label>
                        <input type="text" class="form-control" name="no_seri">
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Nomor Memo / Surat</label>
                            <input type="text" class="form-control" name="no_memo_surat">
                        </div>
                        <div class="form-group">
                            <label for="">Nomor PO</label>
                            <input type="text" class="form-control" name="no_po">
                        </div>
                        <div class="form-group">
                            <label for="">Nomor SP3</label>
                            <input type="text" class="form-control" name="no_sp3">
                        </div>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Spesifikasi</label>
                        <textarea name="spesifikasi" class="form-control" id="" cols="30" rows="10"></textarea>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Nomor Akun</label>
                            <select name="id_kelas_asset" class="form-control" id="kelasAssetCreate">

                            </select>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Asset</label>
                                <div>
                                    <select name="status_kondisi" class="form-control" style="width: 200px" id="">
                                        <option value="">Aktif</option>
                                        <option value="">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Gambar Asset</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Lorem ipsum dolor sit.</span>
                                <label for="gambar_asset" class="btn btn-primary">
                                    Upload
                                    <input type="file" id="gambar_asset" class="d-none" name="gambar_asset">
                                </label>
                            </div>
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
