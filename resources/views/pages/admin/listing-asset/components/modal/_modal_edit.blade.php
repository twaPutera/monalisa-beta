<div class="modal fade modalCreateAsset" id="modalEdit" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Edit Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit" action="{{ route('admin.listing-asset.update', $asset->id) }}" method="POST">
                @csrf
                <div class="modal-body row">
                    <div class="form-group col-md-4 col-6">
                        <label for="">Deskripsi / Nama</label>
                        <input type="text" class="form-control" value="{{ $asset->deskripsi }}" name="deskripsi">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Kelompok Aset</label>
                        <select name="id_group_asset" disabled class="form-control" id="groupAssetCreate">
                            @if (isset($asset->kategori_asset->group_kategori_asset))
                                <option selected="selected" value="{{ $asset->kategori_asset->group_kategori_asset->id }}">{{ $asset->kategori_asset->group_kategori_asset->nama_group }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Jenis Asset</label>
                        <select name="id_kategori_asset" disabled class="form-control" id="kategoriAssetCreate">
                            @if (isset($asset->kategori_asset))
                                <option selected="selected" value="{{ $asset->kategori_asset->id }}">{{ $asset->kategori_asset->nama_kategori }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Kode Asset</label>
                        <input type="text" class="form-control" disabled value="{{ $asset->kode_asset }}" name="kode_asset">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Tanggal Perolehan</label>
                        <input type="text" disabled class="form-control" value="{{ $asset->tanggal_perolehan }}" readonly name="tanggal_perolehan">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Nilai Perolehan (Rp)</label>
                        <input type="number" disabled class="form-control" value="{{ $asset->nilai_perolehan }}" name="nilai_perolehan">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Jenis Perolehan</label>
                        <select class="form-control" name="jenis_penerimaan">
                            <option>Pilih Jenis Perolehan</option>
                            <option {{ $asset->jenis_penerimaan == 'PO' ? 'selected' : '' }} value="PO">PO</option>
                            <option {{ $asset->jenis_penerimaan == 'Hibah' ? 'selected' : '' }} value="Hibah">Hibah</option>
                            <option {{ $asset->jenis_penerimaan == 'Pengadaan' ? 'selected' : '' }} value="Pengadaan">Pengadaan</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Lokasi Asset</label>
                        <select name="id_lokasi" class="form-control" id="lokasiAssetCreate">
                            @if (isset($asset->lokasi))
                                <option selected="selected" value="{{ $asset->lokasi->id }}">{{ $asset->lokasi->nama_lokasi }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Ownership / Dipindahkan Ke</label>
                        <input type="text" disabled class="form-control" value="{{ $asset->owner_name }}">
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Satuan</label>
                        <select name="id_satuan_asset" class="form-control" id="satuanAssetCreate">
                            @if (isset($asset->satuan_asset))
                                <option selected="selected" value="{{ $asset->satuan_asset->id }}">{{ $asset->satuan_asset->nama_satuan }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Vendor</label>
                        <select name="id_vendor" class="form-control" id="vendorAssetCreate">
                            @if (isset($asset->vendor))
                                <option selected="selected" value="{{ $asset->vendor->id }}">{{ $asset->vendor->nama_vendor }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Nomor Seri</label>
                        <input type="text" value="{{ $asset->no_seri }}" class="form-control" name="no_seri">
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Nomor Memo / Surat</label>
                            <input type="text" class="form-control" value="{{ $asset->no_memo_surat }}" name="no_memo_surat">
                        </div>
                        <div class="form-group">
                            <label for="">Nomor PO</label>
                            <input type="text" class="form-control" value="{{ $asset->no_po }}" name="no_po">
                        </div>
                        <div class="form-group">
                            <label for="">Nomor SP3</label>
                            <input type="text" class="form-control" value="{{ $asset->no_sp3 }}" name="no_sp3">
                        </div>
                    </div>
                    <div class="form-group col-md-4 col-6">
                        <label for="">Spesifikasi</label>
                        <textarea name="spesifikasi" class="form-control" id="" cols="30" rows="10">{{ $asset->spesifikasi }}</textarea>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">
                            <label for="">Nomor Akun</label>
                            <select name="id_kelas_asset" class="form-control" id="kelasAssetCreate">
                                @if (isset($asset->kelas_asset))
                                    <option selected="selected" value="{{ $asset->kelas_asset->id }}">{{ $asset->kelas_asset->nama_kelas }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="">Status Asset</label>
                                <div>
                                    <select disabled name="status_kondisi" class="form-control" style="width: 200px"
                                        id="">
                                        <option {{ $asset->status_kondisi == 'Baik' ? 'selected' : '' }} value="Baik">Baik</option>
                                        <option {{ $asset->status_kondisi == 'Rusak' ? 'selected' : '' }} value="Rusak">Rusak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Gambar Asset</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="preview-file-text">No File Choosen</span> <br>
                                    <span id="preview-file-error" class="text-danger"></span>
                                </div>
                                <label for="gambar_asset" class="btn btn-primary">
                                    Upload
                                    <input type="file" id="gambar_asset" accept=".jpeg,.png,.jpg,.gif,.svg"
                                        class="d-none" name="gambar_asset">
                                </label>
                            </div>
                        </div>
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
