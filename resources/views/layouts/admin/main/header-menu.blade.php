<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed " data-ktheader-minimize="on">
    <div class="kt-header__top">
        <div class="kt-container">

            <!-- begin:: Brand -->
            <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
                <div class="kt-header__brand-logo">
                    <a href="{{ url('/admin/dashboard') }}" class="d-inline">
                        <img alt="Logo" src="{{ asset('assets/images/logo-Press-103x75.png') }}"
                            class="kt-header__brand-logo-default" width="50px" />
                        <span class="text-logo text-dark d-sm-none d-md-inline d-none">{{ config('app.name') }}</span>
                    </a>
                </div>
                <div class="kt-header__brand-nav">

                </div>
            </div>

            <!-- end:: Brand -->

            <!-- begin:: Header Topbar -->
            <div class="kt-header__topbar kt-grid__item kt-grid__item--fluid">
                <div class="kt-header__topbar-item d-flex align-items-center mr-3">
                    <div class="badge badge-lg badge-primary"><strong>Jabatan</strong>
                    </div>
                </div>
                <!--begin: User bar -->
                <div class="kt-header__topbar-item kt-header__topbar-item--user">
                    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                        <span class="kt-hidden kt-header__topbar-welcome">Hi,</span>
                        <span class="kt-hidden kt-header__topbar-username">user</span>
                        <img src="https://ui-avatars.com/api/?name=user&background=5174ff&color=fff"
                            id="userDropdown" alt="Profile" class="kt-hidden-">
                        <span class="kt-header__topbar-icon kt-header__topbar-icon--brand kt-hidden"><b>S</b></span>
                    </div>
                    <div
                        class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">

                        <!--begin: Head -->
                        <div class="kt-user-card kt-user-card--skin-light kt-notification-item-padding-x">
                            <div class="kt-user-card__avatar">
                                <img src="https://ui-avatars.com/api/?name=user&background=5174ff&color=fff"
                                    id="userDropdown" alt="Profile" class="kt-hidden-">
                                <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                <span
                                    class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden">S</span>
                            </div>
                            <div class="kt-user-card__name">
                                user
                            </div>
                        </div>

                        <!--end: Head -->

                        <!--begin: Navigation -->
                        <div class="kt-notification">
                            <div class="kt-notification__custom kt-space-end">
                                <form method="GET" action="#">
                                    @csrf
                                    <a class="btn btn-label btn-label-brand btn-sm btn-bold" href="#"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('LOGOUT') }}
                                    </a>
                                </form>
                            </div>
                        </div>

                        <!--end: Navigation -->
                    </div>
                </div>

                <!--end: User bar -->
            </div>

            <!-- end:: Header Topbar -->
        </div>
    </div>
    <div class="kt-header__bottom">
        <div class="kt-container">

            <!-- begin: Header Menu -->
            <button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i
                    class="la la-close"></i></button>
            <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
                <div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile ">
                    <ul class="kt-menu__nav ">
                        <li class="kt-menu__item kt-menu__item--active @if (\Request::segment(2) == 'dashboard') kt-menu__item--active @endif"
                            aria-haspopup="true"><a href="#" class="kt-menu__link "><span
                                    class="kt-menu__link-text">Beranda</span></a></li>
                        <li class="kt-menu__item @if (\Request::segment(2) == 'dashboard') kt-menu__item--active @endif"
                            aria-haspopup="true"><a href="#" class="kt-menu__link "><span
                                    class="kt-menu__link-text">Menu 1</span></a></li>
                        <li class="kt-menu__item @if (\Request::segment(2) == 'dashboard') kt-menu__item--active @endif"
                            aria-haspopup="true"><a href="#" class="kt-menu__link "><span
                                    class="kt-menu__link-text">Menu 2</span></a></li>
                    </ul>
                </div>
            </div>

            <!-- end: Header Menu -->
        </div>
    </div>
</div>
