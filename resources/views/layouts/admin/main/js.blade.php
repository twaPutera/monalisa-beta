<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#0095d6",
                "success": "#359200",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };

    formatNumber = (number) => {
        return number.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
</script>

<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/popper.js/dist/umd/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/js-cookie/src/js.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/moment/min/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/toastr/build/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"
    type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/sticky-js/dist/sticky.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/general/wnumb/wNumb.js') }}" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.3.5/dist/sweetalert2.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> --}}
<script src="{{ asset('custom-js/cek_browser.js') }}"></script>
@yield('plugin_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js"
    integrity="sha256-5UaCo1aRXIRsfnhrev1tfk3LWrfo2Kd/J9DxHm3uVAo=" crossorigin="anonymous"></script>

<script src="{{ asset('assets/js/scripts.bundle.min.js') }}" type="text/javascript"></script>

<script>
    // Set Ajax Header
    $.ajaxSetup({
        headers: {
            "Accept": "application/json",
        }
    });
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    const showToastSuccess = (title, message) => {
        toastr.success(message, title);
    }

    const showToastError = (title, message) => {
        toastr.error(message, title);
    }

    warningMessage = (msg) => {
        swal.fire({
            title: 'Warning!',
            text: msg,
            icon: 'warning',
        });
    }
</script>
<script src="{{ asset('custom-js/config.js') }}" type="text/javascript"></script>
<script src="{{ asset('custom-js/general.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('admin.dashboard.approval') }}",
            type: 'GET',
            dataType: 'json',
            data: {
                url: "{{ \Request::segment(3) }}"
            },
            success: function(response) {
                const tab_peminjaman = $("#peminjaman-approval-count");
                const tab_pemindahan = $("#pemindahan-approval-count");
                const tab_pemutihan = $("#pemutihan-approval-count");
                const daftar_approval = $("#daftar-approval-count");
                const approval_task = $(".approval-task-count");
                if (response.success) {
                    tab_pemindahan.empty();
                    tab_pemutihan.empty();
                    tab_peminjaman.empty();
                    daftar_approval.empty();
                    approval_task.empty();

                    tab_pemindahan.append(response.data.total_approval_pemindahan);
                    tab_pemutihan.append(response.data.total_approval_pemutihan);
                    tab_peminjaman.append(response.data.total_approval_peminjaman);
                    daftar_approval.append(response.data.daftar_approval);
                    approval_task.append(response.data.approval_task);
                }
            }
        })
    });
</script>
@yield('custom_js')
