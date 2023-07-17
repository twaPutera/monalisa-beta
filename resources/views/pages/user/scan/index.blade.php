@extends('layouts.user.master-detail')
@section('page-title', 'Detail Asset')
@section('custom-js')
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                //
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            //
        });
    </script>
    <script src="{{ asset('custom-js/html5-qrcode.min.js') }}"></script>
    <script type="text/javascript">
        function onScanSuccess(qrCodeMessage) {
            $.ajax({
                url: "{{ route('user.scan-qr.find') }}",
                method: 'POST',
                dataType: 'json',
                async: false,
                cache: false,
                data: {
                    kode_asset: qrCodeMessage,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    var success = $('#resultSuccess');
                    var error = $('#resultError');
                    if (result.success) {
                        success.empty();
                        error.addClass('d-none');

                        success.append('<span class="result">' + result.message + '</span>');
                        success.removeClass('d-none');

                        setTimeout(function() {
                            var redirect = "{{ route('user.asset-data.detail', '') }}" + "/" + result
                                .data.id;
                            location.assign(redirect);
                        }, 2000);
                    } else {
                        success.addClass('d-none');
                        error.removeClass('d-none');
                        document.getElementById('resultError').innerHTML = '<span class="result">' +
                            result.message + '</span>';

                    }
                },
                error: function(result) {
                    alert(result.responseText);
                }
            });
        }

        function onScanError(errorMessage) {
            document.getElementById('resultError').innerHTML = '<span class="result">' + errorMessage + '</span>';
        }
        // var html5QrcodeScanner = new Html5QrcodeScanner(
        //     "reader", {
        //         fps: 30,
        //         qrbox: 250,
        //         preferFrontCamera: false,
        //     });
        // html5QrcodeScanner.render(onScanSuccess, onScanError);
        // Membuat opsi kamera dalam elemen select dengan id 'camera-select'
        function createCameraOptions(devices) {
            var cameraSelect = document.getElementById('camera-select');
            cameraSelect.innerHTML = ''; // Menghapus opsi yang ada sebelumnya

            var videoInputs = devices.filter(function(device) {
                return device.kind === 'videoinput';
            });

            videoInputs.forEach(function(device) {
                var option = document.createElement('option');
                option.value = device.deviceId;
                option.text = device.label || 'Kamera ' + (videoInputs.indexOf(device) + 1);
                cameraSelect.appendChild(option);
            });
        }

        navigator.mediaDevices.enumerateDevices()
            .then(function(devices) {
                createCameraOptions(devices);

                var cameraSelect = document.getElementById('camera-select');
                var selectedCameraId = cameraSelect.value;

                const html5QrCode = new Html5Qrcode("reader");
                html5QrCode.start(
                    selectedCameraId, {
                        fps: 30,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    function(decodedText, decodedResult) {
                        onScanSuccess(decodedText);
                    },
                    function(errorMessage) {
                        onScanError(errorMessage);
                    }
                ).catch(function(err) {
                    console.error('Gagal mendapatkan daftar perangkat media:', err);
                });

                // Memperbarui pemindaian saat opsi kamera berubah
                cameraSelect.addEventListener('change', function(event) {
                    selectedCameraId = event.target.value;
                    html5QrCode.stop().then(function() {
                        html5QrCode.start(selectedCameraId);
                    });
                });
            })
            .catch(function(error) {
                console.error('Gagal mendapatkan daftar perangkat media:', error);
            });
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.dashboard.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <div class="section mt-2">
        <h2 style="color: #6F6F6F;"></h2>
        <div class="mt-2">
            <div class="row justify-content-between">
                <div class="col-md-5 col-12">
                    {{-- Scan Div --}}
                    <div id="reader"></div>
                    <select id="camera-select"></select>

                </div>
                <div class="col-md-7 col-12">
                    {{-- Info Div --}}
                    <div class="row gutters">
                        <div class="col-12">
                            <div class="form-section-header light-bg">Hasil Scan
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-danger" id="resultError">Data Tidak Ditemukan, Arahkan Kamera
                                Pada Kode
                                QR Yang Dimiliki Asset</div>

                            <div class="alert alert-success d-none" id="resultSuccess"></div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
@section('button-menu')
    @include('layouts.user.bottom-menu')
@endsection
