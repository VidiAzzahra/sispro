<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}">{{ env('APP_NAME') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">SIPP</a>
        </div>
        <ul class="sidebar-menu">
            {{-- link to dasboard --}}
            <li class="nav-item  {{ Request::is('admin') ? 'active' : '' }}">
                <a href="{{ url('/admin') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            {{-- link to kategori --}}

            <li class="nav-item {{ Request::is('admin/kategori') || Request::is('admin/kategori/*') ? 'active' : '' }}">
                <a href="{{ url('admin/kategori') }}" class="nav-link"><i class="fas fa-columns"></i>
                    <span>Kategori</span></a>
            </li>
            {{-- link to produk --}}
            <li class="nav-item {{ Request::is('admin/produk') || Request::is('admin/produk/*') ? 'active' : '' }}">
                <a href="{{ url('admin/produk') }}" class="nav-link"><i class="fas fa-book"></i> <span>Produk</span></a>
            </li>
            {{-- link to stok --}}
            <li class="nav-item {{ Request::is('admin/stok') || Request::is('admin/stok/*') ? 'active' : '' }}">
                <a href="{{ url('admin/stok') }}" class="nav-link"><i class="fas fa-book"></i> <span>Stok</span></a>
            </li>
        </ul>
        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="{{ url('logout') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fa-solid fa-right-from-bracket"></i>Keluar
            </a>
        </div>
    </aside>
</div>
