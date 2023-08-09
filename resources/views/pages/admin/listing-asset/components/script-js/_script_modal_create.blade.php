<script>
    const generateGroupSelect2 = (idElement) => {
        $('#' + idElement).select2({
            width: '100%',
            placeholder: 'Pilih Kelompok',
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
            placeholder: 'Pilih Jenis',
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

    const generateMemorandumAndinSelect2 = (id = true) => {
        $('.memorandumAndin').select2({
            width: '100%',
            placeholder: 'Pilih Memorandum',
            dropdownParent: $('.modal.show'),
            ajax: {
                url: '{{ route('andin-api.find-data-memorandum') }}',
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
        }).on('change', function(e) {
            const data = $(this).select2('data')[0];
            if (id) {
                $('#noMemoSurat').val(data.text);
            } else {
                $('.noMemoSurat').val(data.text);
            }
        });
    }

    $('.modalCreateAssetService').on('shown.bs.modal', function() {
        setTimeout(() => {
            // generateSelect2Lokasi('lokasiAssetCreateService');
            generateSelect2KategoriService();
        }, 2000);
    });

    $('.modalCreateAsset').on('shown.bs.modal', function() {
        setTimeout(() => {
            generateGroupSelect2('groupAssetCreate');
            generateSelect2Lokasi('lokasiAssetCreate');
            generateKelasAsset();
            generateSatuanAsset();
            generateVendorAsset();
            generateOwnerAsset();
            generateMemorandumAndinSelect2();
            select2StatusKondisi();
            select2StatusAkunting();
        }, 2000);
    });

    $('.modalEditAsset').on('shown.bs.modal', function() {
        setTimeout(() => {
            generateGroupSelect2('groupAssetCreate');
            $('#lokasiAssetEdit').select2({
                width: '100%',
                placeholder: 'Pilih Lokasi',
                dropdownParent: $('.modal.show'),
            });
            generateKelasAsset();
            generateSatuanAsset();
            generateVendorAsset();
            generateOwnerAsset();
            generateMemorandumAndinSelect2();
        }, 2000);
    });

    $('.modalCreateOpname').on('shown.bs.modal', function() {
        $('#lokasiAssetOpname').select2({
            width: '100%',
            placeholder: 'Pilih Lokasi',
            dropdownParent: $('.modal.show'),
        });
        select2StatusKondisi();
        select2StatusAkunting();
    });

    $('#groupAssetCreate').on('change', function() {
        generateKategoriSelect2Create('kategoriAssetCreate', $(this).val());
    });

    $('#groupAssetSearch').on('change', function() {
        generateKategoriSelect2Create('kategoriAssetSearch', $(this).val());
    });

    $('#lokasiAssetCreateService').on('change', function() {
        generateAssetSelect2Create('listAssetLocation', $(this).val());
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
            placeholder: 'Pilih Jenis',
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

    const generateSelect2KategoriService = () => {
        $('#kategoriServiceCreate').select2({
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

    $("#kategoriAssetSearch").on('change', function() {
        generateSelect2AssetDataSearch();
    });

    const generateSelect2AssetDataSearch = () => {
        $('#assetDataSearch').select2({
            width: '100%',
            placeholder: 'Pilih Asset',
            dropdownParent: $('#modalSearchAsset'),
            ajax: {
                url: '{{ route('admin.listing-asset.get-all-data-asset-select2') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        keyword: params.term, // search term
                        id_kategori_asset: $('#kategoriAssetSearch').val(),
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

    $("#buttonSearchAsset").on('click', function() {
        $('#asal_asset_preview').val($("#assetDataSearch").children("option:selected").text());
        $('#asal_asset_id').val($("#assetDataSearch").val());
        $('#asal_asset_preview_edit').val($("#assetDataSearch").children("option:selected").text());
        $('#asal_asset_id_edit').val($("#assetDataSearch").val());
        $('#modalSearchAsset').modal('hide');
        $('.select2-results').empty();
        $('.select2-dropdown').remove();
    });

    const jenisAssetChange = (select) => {
        const assetLama = $(select).children("option:selected").data('asset-lama');
        if (assetLama == "1") {
            $('#asal-asset-container').show();
            $("#modalSearchAsset").on('shown.bs.modal', function() {
                generateGroupSelect2('groupAssetSearch');
            }).modal('show');
        } else {
            $('#asal-asset-container').hide();
            $('#asal_asset_preview').val("");
            $('#asal_asset_id').val("");
        }
    }

    const jenisAssetChangeEdit = (select) => {
        const assetLama = $(select).children("option:selected").data('asset-lama');
        if (assetLama == "1") {
            $('#asal-asset-container-edit').show();
            $("#modalSearchAsset").on('shown.bs.modal', function() {
                generateGroupSelect2('groupAssetSearch');
            }).modal('show');
        } else {
            $('#asal-asset-container-edit').hide();
            $('#asal_asset_preview_edit').val("");
            $('#asal_asset_id_edit').val("");
        }
    }

    const changeStatusKondisiAsset = (value, id) => {
        const targetSelect = $('#' + id);
        if (value == "tidak-ditemukan") {
            targetSelect.find('option[value="TX"]').prop('selected', true);
        }
        if (id == "status_akunting") {
            select2StatusAkunting();
        }

        if (id == "status_akunting_edit") {
            select2StatusAkuntingEdit();
        }
    }

    const select2StatusAkunting = () => {
        $('#status_akunting').select2({
            width: '60%',
            placeholder: 'Pilih Status Akunting',
            dropdownParent: $('.modal.show'),
        });
    }

    const select2StatusKondisi = () => {
        $('#status_kondisi').select2({
            width: '60%',
            placeholder: 'Pilih Status Kondisi',
            dropdownParent: $('.modal.show'),
        });
    }
</script>
