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
            @if (\Request::segment(3) == 'kategori-asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.kategori-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-list"></i>
            </span>
            <span class="kt-nav__link-text">Kategori Asset</span> </a>
    </li>
    <li class="kt-nav__item">
        <a
            @if (\Request::segment(3) == 'satuan-asset') class="kt-nav__link px-3 active" href="javascript:;"
        @else
            class="kt-nav__link px-3" href="{{ route('admin.setting.satuan-asset.index') }}" @endif>
            <span class="kt-nav__link-icon">
                <i class="fas fa-box"></i>
            </span>
            <span class="kt-nav__link-text">Satuan</span> </a>
    </li>
</ul>
