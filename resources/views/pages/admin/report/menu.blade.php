<ul class="kt-nav kt-nav--bold my-kt-nav-blue3 pt-0 shadow-custom">
    <li class="kt-nav__item">
        <a style="border-top-right-radius: 6px; border-top-left-radius: 6px;"
            @if (\Request::segment(3) == 'summary-asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.report.summary-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-box"></i>
            </span>
            <span class="kt-nav__link-text">Summary Asset</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'depresiasi') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.report.depresiasi.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-file"></i>
            </span>
            <span class="kt-nav__link-text">Depresiasi</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'history-pengaduan') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.report.history-pengaduan.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-clipboard-check"></i>
            </span>
            <span class="kt-nav__link-text">History Pengaduan</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'history-service') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.report.history-service.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-list"></i>
            </span>
            <span class="kt-nav__link-text">History Service</span> </a>
    </li>
</ul>
