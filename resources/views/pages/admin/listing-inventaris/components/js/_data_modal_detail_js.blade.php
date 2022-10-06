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
                url: "{{ route('admin.listing-inventaris.datatable.penambahan') }}",
                data: function(d) {
                    d.id_inventaris = "{{ $listing_inventaris->id }}"
                }
            },
            columns: [{
                    data: 'tanggal'
                },
                {
                    name: 'jumlah',
                    data: 'jumlah'
                },
                {
                    data: 'harga_beli'
                },
                {
                    data: 'created_by'
                },

            ],
            columnDefs: [
                //Custom template data
            ],
        });

        var table2 = $('#datatableLogPengurangan');
        table2.DataTable({
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
                url: "{{ route('admin.listing-inventaris.datatable.pengurangan') }}",
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
                    data: 'created_by'
                },

            ],
            columnDefs: [
                //Custom template data
            ],
        });
    });
</script>
