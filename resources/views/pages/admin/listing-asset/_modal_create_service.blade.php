<div class="modal fade modalCreateAssetService" id="modalCreateAssetService" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
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
                <div class="modal-body row">
                    <div class="col-md-6 col-6">
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
                            <label for="">Status Servis </label>
                            <select name="status_service" class="form-control">
                                <option value="onprogress">On Progress</option>
                                <option value="backlog">Backlog</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="form-group">
                            <label for="">Kondisi Asset Sebelum Servis</label>
                            <textarea name="kondisi_sebelum" class="form-control" id="" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Bukti Service</label>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span id="preview-file-image-text">No File Choosen</span> <br>
                                    <span id="preview-file-image-error" class="text-danger"></span>
                                </div>
                                <label for="file_asset_service" class="btn btn-primary">
                                    Upload
                                    <input type="file" id="file_asset_service" accept=".jpeg,.png,.jpg,.gif,.svg"
                                        class="d-none" name="file_asset_service">
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
