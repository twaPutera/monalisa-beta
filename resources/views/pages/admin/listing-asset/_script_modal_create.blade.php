<script>
    const generateGroupSelect2 = (idElement) => {
        $('#' + idElement).select2({
            width: '100%',
            placeholder: 'Pilih Group',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.group-kategori-asset.get-data-select2') }}',
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

    const generateKategoriSelect2Create = (idElement, idGroup) => {
        $('#' + idElement).removeAttr('disabled');
        $('#' + idElement).select2({
            width: '100%',
            placeholder: 'Pilih Kategori',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.kategori-asset.get-data-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        id_group_kategori_asset: idGroup,
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

    $('.modalCreateAsset').on('shown.bs.modal', function() {
        setTimeout(() => {
            generateGroupSelect2('groupAssetCreate');
            generateSelect2Lokasi();
            generateKelasAsset();
            generateSatuanAsset();
            generateVendorAsset();
            generateOwnerAsset();
        }, 2000);
    });

    $('#groupAssetCreate').on('change', function() {
        generateKategoriSelect2Create('kategoriAssetCreate', $(this).val());
    });

    const generateSelect2Lokasi = () => {
        $('#lokasiAssetCreate').select2({
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

    const generateKelasAsset = () => {
        $('#kelasAssetCreate').select2({
            width: '100%',
            placeholder: 'Pilih Kelas',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.kelas-asset.get-data-select2') }}',
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

    const generateVendorAsset = () => {
        $('#vendorAssetCreate').select2({
            width: '100%',
            placeholder: 'Pilih Vendor',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.vendor.get-data-select2') }}',
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

    const generateSatuanAsset = () => {
        $('#satuanAssetCreate').select2({
            width: '100%',
            placeholder: 'Pilih Satuan',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.setting.satuan-asset.get-data-select2') }}',
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

    const generateOwnerAsset = () => {
        $('#ownershipAssetCreate').select2({
            width: '100%',
            placeholder: 'Pilih Pemegang',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('admin.listing-asset.get-all-data-owner-select2') }}',
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