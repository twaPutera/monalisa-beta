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
