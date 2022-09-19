<div class="modal fade modalFilterAsset" id="modalFilter" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Filter Asset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="form-group col-md-6 col-12">
                    <label for="">Kategori Asset</label>
                    <select name="id_kategori_asset" class="form-control" id="kategoriAssetFilter">

                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Satuan</label>
                    <select name="id_satuan_asset" class="form-control" id="satuanAssetFilter">

                    </select>
                </div>
                <div class="form-group col-md-6 col-12">
                    <label for="">Vendor</label>
                    <select name="id_vendor" class="form-control" id="vendorAssetFilter">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" onclick="filterTableAsset()" data-dismiss="modal" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </div>
</div>
