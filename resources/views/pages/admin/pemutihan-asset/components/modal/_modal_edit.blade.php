<div class="modal fade modalEditInventarisData" id="modalEditInventarisData" role="dialog" aria-labelledby=""
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">List Asset Yang Akan Diputihkan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-remove"></span>
                </button>
            </div>
            <form class="kt-form kt-form--fit kt-form--label-right form-submit"
                action="{{ route('admin.pemutihan-asset.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0 editAssetData" id="editAssetData">
                                <thead>
                                    <tr>
                                        <th width="50px" class="text-center pl-5">
                                            <div class="form-check form-check-inline ms-1">
                                                <input type="checkbox" class="form-check-input check-all"
                                                    id=" exampleCheck1">
                                                <label class="form-check-label" for="exampleCheck1"></label>
                                            </div>
                                        </th>
                                        <th class="text-center">Kode Asset</th>
                                        <th>Jenis Asset</th>
                                        <th>Lokasi Asset</th>
                                        <th>Kondisi Asset</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
