<ul class="nav nav-tabs  nav-tabs-line" role="tablist">
    <li class="nav-item">
        <a class="nav-link @if (\Request::segment(3) == 'peminjaman') active @endif"
            href="{{ route('admin.approval.peminjaman.index') }}">Peminjaman ({{ $total_approval_peminjaman }})</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link @if (\Request::segment(3) == 'pemutihan') active @endif"
            href="{{ route('admin.approval.pemutihan.index') }}">Pemutihan ({{ $total_approval_pemutihan }})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if (\Request::segment(3) == 'pemindahan') active @endif"
            href="{{ route('admin.approval.pemindahan.index') }}">Pemindahan ({{ $total_approval_pemindahan }})</a>
    </li>
</ul>
