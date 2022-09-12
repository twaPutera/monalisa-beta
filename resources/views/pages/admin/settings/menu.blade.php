<ul class="kt-nav kt-nav--bold my-kt-nav-blue3 pt-0 shadow-custom">
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'config') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="#" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-cog"></i>
            </span>
            <span class="kt-nav__link-text">Config</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'lokasi') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.lokasi.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-map"></i>
            </span>
            <span class="kt-nav__link-text">Master Lokasi</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'kategori-asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.kategori-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-list"></i>
            </span>
            <span class="kt-nav__link-text">Kategori Asset</span> </a>
    </li>
</ul>
