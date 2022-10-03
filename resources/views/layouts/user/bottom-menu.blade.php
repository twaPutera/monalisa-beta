<a href="{{ route('user.dashboard.index') }}" class="item @if(\Request::segment(2) == 'dashboard') active @endif">
    <div class="col">
        <ion-icon name="pie-chart-outline"></ion-icon>
        <strong>Dashboard</strong>
    </div>
</a>
<a href="app-pages.html" class="item">
    <div class="col">
        <ion-icon name="warning-outline"></ion-icon>
        <strong>Keluhan</strong>
    </div>
</a>
<a href="app-components.html" class="item">
    <div class="col">
        <ion-icon name="file-tray-stacked-outline"></ion-icon>
        <strong>Stock Opname</strong>
    </div>
</a>
<a href="{{ route('user.approval.index') }}" class="item @if(\Request::segment(2) == 'approval') active @endif">
    <div class="col">
        <ion-icon name="checkmark-circle-outline"></ion-icon>
        <strong>Approval</strong>
    </div>
</a>
<a href="app-settings.html" class="item">
    <div class="col">
        <ion-icon name="albums-outline"></ion-icon>
        <strong>Daftar Aset</strong>
    </div>
</a>