<ul class="nav nav-tabs  nav-tabs-line" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if(\Request::segment(3) == 'peminjaman') active @endif" href="{{ route('admin.approval.peminjaman.index') }}">Peminjaman (2)</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link @if(\Request::segment(3) == 'pemutihan') active @endif" href="#kt_tabs_1_2">Pemutihan (2)</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if(\Request::segment(3) == 'pemindahan') active @endif" href="#kt_tabs_1_3">Pemindahan (2)</a>
    </li>
</ul>