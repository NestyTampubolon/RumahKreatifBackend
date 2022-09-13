<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="index3.html" class="brand-link" style="background-color: #f8f9fa">
    <img src="{{ URL::asset('asset/Image/logo_rkt.png') }}" alt="Logo" class="brand-image">
    <span class="brand-text font-weight-light" style="color:black">Rumah Kreatif Toba</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ URL::asset('asset/AdminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">Alexander Pierce</a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
            <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
            </button>
            </div>
        </div>
    </div> -->

    <!-- Sidebar Menu -->
    <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
            
        <!-- <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
                <i class="right fas fa-angle-left"></i>
            </a>
        </li> -->
        <li class="nav-item">
            <a href="./" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <li class="nav-header">USERS</li>
        <li class="nav-item">
            <a href="./verifikasi_user" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Verifikasi User</p>
            </a>
        </li>
        <li class="nav-header">TOKO</li>
        <li class="nav-item">
            <a href="./toko_user" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Toko User</p>
            </a>
        </li>
        <li class="nav-header">PRODUK</li>
        <li class="nav-item">
            <a href="./produk" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Produk</p>
            </a>
        </li>
        <li class="nav-header">KATEGORI</li>
        <li class="nav-item">
            <a href="./kategori_produk" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Kategori</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="./kategori_tipe_spesifikasi" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Kategori Tipe Spesifikasi</p>
            </a>
        </li>
        <li class="nav-header">SPESIFIKASI</li>
        <li class="nav-item">
            <a href="./tipe_spesifikasi" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Tipe Spesifikasi</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="./spesifikasi" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Spesifikasi</p>
            </a>
        </li>
        <li class="nav-header">BANK</li>
        <li class="nav-item">
            <a href="./bank" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>Bank</p>
            </a>
        </li>
        <li class="nav-header"></li>
        <li class="nav-item">
            <a href="./logout" class="nav-link">
                <i class="nav-icon far fa-circle text-danger"></i>
                <p class="text">Keluar</p>
            </a>
        </li>
    </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
