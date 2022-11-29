@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

<link rel="stylesheet" href="../bootstrap/pesananku_all.css">

@section('container')

<div class="card-header px-4 py-5">
    <h5 class="text-muted mb-0">Daftar Pembelian <span style="color: #800000;">Pelanggan</span></h5>
</div>

<div class="card-body p-4">

    <div class="col-md-16">
        <ul class="nav nav-tabs nav-tabs-bg" id="tabs-1" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab_semua_pembelian_tab" data-toggle="tab" href="#tab_semua_pembelian" role="tab" aria-controls="tab_semua_pembelian" aria-selected="true">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pembelian_belum_dikemas_tab" data-toggle="tab" href="#tab_pembelian_belum_dikemas" role="tab" aria-controls="tab_pembelian_belum_dikemas" aria-selected="false">Perlu Dikemas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pembelian_dalam_perjalanan_tab" data-toggle="tab" href="#tab_pembelian_dalam_perjalanan" role="tab" aria-controls="tab_pembelian_dalam_perjalanan" aria-selected="false">Dalam Perjalanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pembelian_belum_diambil_tab" data-toggle="tab" href="#tab_pembelian_belum_diambil" role="tab" aria-controls="tab_pembelian_belum_diambil" aria-selected="false">Belum Diambil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pembelian_belum_dikonfirmasi_pembeli_tab" data-toggle="tab" href="#tab_pembelian_belum_dikonfirmasi_pembeli" role="tab" aria-controls="tab_pembelian_belum_dikonfirmasi_pembeli" aria-selected="false">Belum Dikonfirmasi Pembeli</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pembelian_berhasil_tab" data-toggle="tab" href="#tab_pembelian_berhasil" role="tab" aria-controls="tab_pembelian_berhasil" aria-selected="false">Berhasil [Belum Konfirmasi Pembayaran]</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pembelian_telah_dibayar_tab" data-toggle="tab" href="#tab_pembelian_telah_dibayar" role="tab" aria-controls="tab_pembelian_telah_dibayar" aria-selected="false">Berhasil [Telah Konfirmasi Pembayaran]</a>
            </li>
        </ul>
        <div class="tab-content tab-content-border" id="tab-content-1">

            <div class="tab-pane fade show active" id="tab_semua_pembelian" role="tabpanel" aria-labelledby="tab_semua_pembelian_tab">
                @include('user.toko.tab_semua_pembelian')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade show" id="tab_pembelian_belum_dikemas" role="tabpanel" aria-labelledby="tab_pembelian_belum_dikemas_tab">
                @include('user.toko.tab_pembelian_belum_dikemas')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pembelian_dalam_perjalanan" role="tabpanel" aria-labelledby="tab_pembelian_dalam_perjalanan_tab">
                @include('user.toko.tab_pembelian_dalam_perjalanan')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pembelian_belum_diambil" role="tabpanel" aria-labelledby="tab_pembelian_belum_diambil_tab">
                @include('user.toko.tab_pembelian_belum_diambil')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pembelian_belum_dikonfirmasi_pembeli" role="tabpanel" aria-labelledby="tab_pembelian_belum_dikonfirmasi_pembeli_tab">
                @include('user.toko.tab_pembelian_belum_dikonfirmasi_pembeli')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pembelian_berhasil" role="tabpanel" aria-labelledby="tab_pembelian_berhasil_tab">
                @include('user.toko.tab_pembelian_berhasil')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pembelian_telah_dibayar" role="tabpanel" aria-labelledby="tab_pembelian_telah_dibayar_tab">
                @include('user.toko.tab_pembelian_telah_dibayar')
            </div><!-- .End .tab-pane -->
            
        </div><!-- End .tab-content -->
    </div><!-- End .col-md-6 -->

</div><!-- End .col-md-6 -->

@endsection

