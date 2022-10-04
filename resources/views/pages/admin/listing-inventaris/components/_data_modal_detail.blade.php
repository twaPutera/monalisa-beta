<div class="col-md-4 col-12">
    <div class="pt-3 pb-1" style="border-radius: 9px; background: #E5F3FD;">
        <table id="tableProperti" class="table table-striped">
            <tr>
                <td width="40%">Jenis Inventaris</td>
                <td><strong>{{ $listing_inventaris->kode_inventori }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Kategori Inventaris</td>
                <td><strong>{{ $listing_inventaris->kategori_inventori->nama_kategori }}</strong>
                </td>
            </tr>
            <tr>
                <td width="40%">Merk Inventaris</td>
                <td><strong>{{ $listing_inventaris->nama_inventori }}</strong></td>
            </tr>
            <tr>
                <td width="40%">Jumlah Inventaris Sebelumnya</td>
                <td><strong>{{ $listing_inventaris->jumlah_sebelumnya }}
                        {{ $listing_inventaris->satuan_inventori->nama_satuan }}</strong></td>
            </tr>
            <tr>
                <td width="40%">Jumlah Inventaris Saat Ini</td>
                <td><strong>{{ $listing_inventaris->jumlah_saat_ini }}
                        {{ $listing_inventaris->satuan_inventori->nama_satuan }}</strong></td>
            </tr>
            <tr>
                <td width="40%">Harga Beli</td>
                <td><strong>{{ $listing_inventaris->harga_beli }}</strong></td>
            </tr>
            <tr>
                <td width="40%">Deskripsi Inventaris</td>
                <td><strong>{{ $listing_inventaris->deskripsi_inventori }}</strong></td>
            </tr>
        </table>
    </div>
</div>
<div class="col-12 col-md-8">
    <div class="table-responsive">
        <table class="table table-striped mb-0" id="datatableLogService">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No Memo</th>
                    <th>Jumlah Inventaris</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#datatableLogService');
        table.DataTable({
            responsive: true,
            processing: true,
            searching: false,
            ordering: false,
            serverSide: true,
            bLengthChange: false,
            autoWidth: false,
            paging: false,
            info: false,
            ajax: {
                url: "{{ route('admin.listing-inventaris.datatable.stok') }}",
                data: function(d) {
                    d.id_inventaris = "{{ $listing_inventaris->id }}"
                }
            },
            columns: [{
                    data: 'tanggal'
                },
                {
                    data: 'no_memo'
                },
                {
                    name: 'jumlah',
                    data: 'jumlah'
                },
                {
                    name: 'status',
                    data: 'status'
                },

            ],
            columnDefs: [
                //Custom template data
            ],
        });
    });
</script>
