<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed " data-ktheader-minimize="on">
    <div class="kt-header__top">
        <div class="kt-container">

            <!-- begin:: Brand -->
            <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
                <div class="kt-header__brand-logo">
                    <a href="{{ url('/admin/dashboard') }}" class="d-flex align-items-center">
                        <img alt="Logo" src="{{ asset('assets/images/logo-Press-103x75.png') }}"
                            class="kt-header__brand-logo-default" width="50px" />
                        <span
                            class="text-logo text-dark d-sm-none d-md-inline d-none ml-3">{{ config('app.name') }}</span>
                    </a>
                </div>
                <div class="kt-header__brand-nav">

                </div>
            </div>

            <!-- end:: Brand -->

            <!-- begin:: Header Topbar -->
            <div class="kt-header__topbar kt-grid__item kt-grid__item--fluid">
                <div class="kt-header__topbar-item dropdown">
                    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,10px">
                        <span class="kt-header__topbar-icon shadow-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="25.408"
                                viewBox="0 0 22.783 25.408">
                                <path id="notification"
                                    d="M25.892,18.035l-2.366-2.366v-4.03A8.823,8.823,0,0,0,15.64,2.877V1.125H13.888V2.877A8.893,8.893,0,0,0,6,11.639v4.03L3.637,18.035a.8.8,0,0,0-.262.613v2.628a.823.823,0,0,0,.876.876h6.133a4.381,4.381,0,0,0,8.761,0h6.133a.823.823,0,0,0,.876-.876V18.648A.8.8,0,0,0,25.892,18.035ZM14.764,24.781a2.628,2.628,0,0,1-2.628-2.628h5.257a2.628,2.628,0,0,1-2.628,2.628ZM24.4,20.4H5.127V19l2.366-2.366a.8.8,0,0,0,.262-.613V11.639a7.009,7.009,0,1,1,14.018,0v4.381a.8.8,0,0,0,.262.613L24.4,19Z"
                                    transform="translate(-3.373 -1.125)" fill="#0067d4" />
                            </svg>
                            <span class="kt-badge kt-badge--success kt-hidden"></span>
                        </span>
                    </div>
                    <div
                        class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
                        <form>

                            <!--begin: Head -->
                            <div class="kt-head kt-head--skin-light kt-head--fit-x kt-head--fit-b">
                                <h3 class="kt-head__title">
                                    User Notifications
                                    &nbsp;
                                    <span class="btn btn-label-primary btn-sm btn-bold btn-font-md">23 new</span>
                                </h3>
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-brand  kt-notification-item-padding-x"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active show" data-toggle="tab"
                                            href="#topbar_notifications_notifications" role="tab"
                                            aria-selected="true">Alerts</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#topbar_notifications_events"
                                            role="tab" aria-selected="false">Events</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#topbar_notifications_logs"
                                            role="tab" aria-selected="false">Logs</a>
                                    </li>
                                </ul>
                            </div>

                            <!--end: Head -->
                            <div class="tab-content">
                                <div class="tab-pane active show" id="topbar_notifications_notifications"
                                    role="tabpanel">
                                    <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll"
                                        data-scroll="true" data-height="300" data-mobile-height="200">
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-line-chart kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New order has been received
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    2 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-box-1 kt-font-brand"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New customer is registered
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    3 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-chart2 kt-font-danger"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    Application has been approved
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    3 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-image-file kt-font-warning"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New file has been uploaded
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    5 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-bar-chart kt-font-info"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New user feedback received
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    8 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-pie-chart-2 kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    System reboot has been successfully completed
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    12 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-favourite kt-font-danger"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New order has been placed
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    15 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item kt-notification__item--read">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-safe kt-font-primary"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    Company meeting canceled
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    19 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-psd kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New report has been received
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    23 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon-download-1 kt-font-danger"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    Finance report has been generated
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    25 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon-security kt-font-warning"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New customer comment recieved
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    2 days ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-pie-chart kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New customer is registered
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    3 days ago
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
                                    <div class="kt-notification kt-margin-t-10 kt-margin-b-10 kt-scroll"
                                        data-scroll="true" data-height="300" data-mobile-height="200">
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-psd kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New report has been received
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    23 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon-download-1 kt-font-danger"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    Finance report has been generated
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    25 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-line-chart kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New order has been received
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    2 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-box-1 kt-font-brand"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New customer is registered
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    3 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-chart2 kt-font-danger"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    Application has been approved
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    3 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-image-file kt-font-warning"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New file has been uploaded
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    5 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-bar-chart kt-font-info"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New user feedback received
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    8 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-pie-chart-2 kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    System reboot has been successfully completed
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    12 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-favourite kt-font-brand"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New order has been placed
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    15 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item kt-notification__item--read">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-safe kt-font-primary"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    Company meeting canceled
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    19 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-psd kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New report has been received
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    23 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon-download-1 kt-font-danger"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    Finance report has been generated
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    25 hrs ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon-security kt-font-warning"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New customer comment recieved
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    2 days ago
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-pie-chart kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title">
                                                    New customer is registered
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    3 days ago
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
                                    <div class="kt-grid kt-grid--ver" style="min-height: 200px;">
                                        <div
                                            class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                                            <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                                All caught up!
                                                <br>No new notifications.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="kt-header__topbar-item d-flex align-items-center mr-3">
                    <div class="badge badge-lg badge-primary"><strong>Jabatan</strong>
                    </div>
                </div>
                <!--begin: User bar -->
                <div class="kt-header__topbar-item kt-header__topbar-item--user">
                    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                        <span class="kt-hidden kt-header__topbar-welcome">Hi,</span>
                        <span class="kt-hidden kt-header__topbar-username">user</span>
                        <img src="https://ui-avatars.com/api/?name=user&background=5174ff&color=fff" id="userDropdown"
                            alt="Profile" class="kt-hidden-">
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
                        <li class="kt-menu__item @if (\Request::segment(2) == 'dashboard') kt-menu__item--active @endif"
                            aria-haspopup="true"><a href="./index.html" class="kt-menu__link "><span
                                    class="kt-menu__link-text"><span class="kt-menu__link-icon"><i
                                            class="fa fa-home"></i></span> Home</span></a>
                        </li>
                        <li class="kt-menu__item" aria-haspopup="true"><a href="./listing-asset.html"
                                class="kt-menu__link "><span class="kt-menu__link-text"><span
                                        class="kt-menu__link-icon"><i class="fa fa-list"></i></span> Listing
                                    Asset</span></a></li>
                        <li class="kt-menu__item" aria-haspopup="true"><a href="./jumlah-asset.html"
                                class="kt-menu__link "><span class="kt-menu__link-text"><span
                                        class="kt-menu__link-icon"><i class="fa fa-list"></i></span>
                                    Pemeliharaan</span></a></li>
                        <li class="kt-menu__item" aria-haspopup="true"><a href="./certification.html"
                                class="kt-menu__link "><span class="kt-menu__link-text"><span
                                        class="kt-menu__link-icon"><i class="fas fa-clipboard-check"></i></span>
                                    Daftar Laporan</span></a></li>
                        <li class="kt-menu__item" aria-haspopup="true"><a href="./certification.html"
                                class="kt-menu__link "><span class="kt-menu__link-text"><span
                                        class="kt-menu__link-icon"><i class="fas fa-print"></i></span> Report
                                    Asset</span></a></li>
                        <li class="kt-menu__item @if (\Request::segment(2) == 'setting') kt-menu__item--active @endif"
                            aria-haspopup="true"><a href="{{ route('admin.setting.kategori-asset.index') }}"
                                class="kt-menu__link "><span class="kt-menu__link-text"><span
                                        class="kt-menu__link-icon"><i class="fas fa-cog"></i></span>
                                    Setting</span></a></li>
                    </ul>
                </div>
            </div>

            <!-- end: Header Menu -->
        </div>
    </div>
</div>
