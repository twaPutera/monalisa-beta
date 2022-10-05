<!doctype html>
<html lang="en">

<head>
    @include('layouts.user.head')
</head>

<body>

    <!-- loader -->
    <div id="loader">
        <img src="/assets/user/img/loading-icon.png" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            @yield('back-button')
        </div>
        <div class="pageTitle">Page Title</div>
        <div class="right">
            {{-- <a href="#" class="headerButton">
                <ion-icon name="notifications-outline" role="img" class="md hydrated" aria-label="notifications outline"></ion-icon>
            </a> --}}
        </div>
    </div>
    <!-- * App Header -->


    <!-- App Capsule -->
    <div id="appCapsule">
        @yield('content')
        <!-- app footer -->
        {{-- <div class="appFooter">
            <div class="footer-title">
                Copyright © Finapp 2021. All Rights Reserved.
            </div>
            Bootstrap 5 based mobile template.
        </div> --}}
        <!-- * app footer -->

    </div>
    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        @yield('button-menu')
    </div>
    <!-- * App Bottom Menu -->
    <!-- * App Sidebar -->

    <!-- ========= JS Files =========  -->
    @include('layouts.user.js')

</body>

</html>