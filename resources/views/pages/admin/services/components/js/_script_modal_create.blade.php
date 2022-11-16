<script>
    $('.modalCreateAssetService').on('shown.bs.modal', function() {
        setTimeout(() => {
            generateSelect2Lokasi('lokasiAssetCreateService');
            generateAssetSelect2Create('listAssetLocation', 'root');
            generateSelect2KategoriService('kategoriServiceCreate');
            generateAssetServiceDateSelect2Create('listAssetServicesDate');
        }, 2000);
    });
    $('#lokasiAssetCreateService').on('change', function() {
        generateAssetSelect2Create('listAssetLocation', $(this).val());
    });

    $('#listAssetLocation').on('change', function() {
        generateAssetServiceDateSelect2Create('listAssetServicesDate', $(this).val());
    });

    $('#listAssetLocationUpdate').on('change', function() {
        generateAssetServiceDateSelect2Update('listAssetServicesDateUpdate', $(this).val());
    });

    $('#lokasiAssetUpdateService').on('change', function() {
        generateAssetSelect2Create('listAssetLocationUpdate', $(this).val());
    });

    $('#listAssetLocation').on('change', function() {
        generateSelect2Lokasi('lokasiAssetCreateService');
    });

    const generateSelect2Lokasi = (id) => {
        $('#' + id).select2({
            width: '100%',
            placeholder: 'Pilih Lokasi',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.lokasi.get-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                    };
                },
                cache: true
            },
        });
    }

    const generateAssetSelect2Create = (idElement, idLokasi) => {
        $('#' + idElement).removeAttr('disabled');
        $('#' + idElement).select2({
            width: '100%',
            placeholder: 'Pilih Asset',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.listing-asset.get-all-data-asset-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        id_lokasi: idLokasi,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                    };
                },
                cache: true
            },
        });
    }

    const generateAssetServiceDateSelect2Create = (idElement, idAsset) => {
        $('#' + idElement).removeAttr('disabled');
        $('#' + idElement).select2({
            width: '100%',
            placeholder: 'Pilih Tanggal Service',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.services.get-data-perencanaan-service') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        id_asset: idAsset,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                    };
                },
                cache: true
            },
        });
    }

    const generateAssetServiceDateSelect2Update = (idElement, idAsset) => {
        console.log(idElement, idAsset);
        $('.' + idElement).removeAttr('disabled');
        $('.' + idElement).select2({
            width: '100%',
            placeholder: 'Pilih Tanggal Service',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.services.get-data-perencanaan-service') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        id_asset: idAsset,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                    };
                },
                cache: true
            },
        });
    }

    const generateSelect2KategoriService = (id) => {
        $('#' + id).select2({
            width: '100%',
            placeholder: 'Pilih Kategori Service',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.kategori-service.get-data-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                    };
                },
                cache: true
            },
        });
    }
</script>
