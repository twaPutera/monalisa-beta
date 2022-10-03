<!-- Bootstrap -->
<script src="{{ asset('assets/user/js/lib/bootstrap.bundle.min.js') }}"></script>
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<!-- Splide -->
<script src="{{ asset('assets/user/js/plugins/splide/splide.min.js') }}"></script>
@yield('pluggin-js')
<!-- Base Js File -->
<script src="{{ asset('assets/user/js/base.js') }}"></script>

<script>
    // Add to Home with 2 seconds delay.
    AddtoHome("2000", "once");
</script>

@yield('custom-js')