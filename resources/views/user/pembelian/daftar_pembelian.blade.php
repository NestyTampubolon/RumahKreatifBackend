@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

<link rel="stylesheet" href="../bootstrap/pesananku_all.css">

@section('container')

<div class="card-header px-4 py-5">
    <h5 class="text-muted mb-0">Pesanan Anda, <span style="color: #800000;">{{$profile->name}}</span>!</h5>
</div>

<div class="card-body p-4">

    <div class="col-md-16">
        <ul class="nav nav-tabs nav-tabs-bg" id="tabs-1" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab_semua_pesanan_tab" data-toggle="tab" href="#tab_semua_pesanan" role="tab" aria-controls="tab_semua_pesanan" aria-selected="true">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pesanan_belum_bayar_tab" data-toggle="tab" href="#tab_pesanan_belum_bayar" role="tab" aria-controls="tab_pesanan_belum_bayar" aria-selected="false">Belum Bayar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pembayaran_belum_dikonfirmasi_tab" data-toggle="tab" href="#tab_pembayaran_belum_dikonfirmasi" role="tab" aria-controls="tab_pembayaran_belum_dikonfirmasi" aria-selected="false">Pembayaran Belum Dikonfirmasi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_sedang_dikemas_tab" data-toggle="tab" href="#tab_sedang_dikemas" role="tab" aria-controls="tab_sedang_dikemas" aria-selected="false">Sedang Dikemas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pesanan_dalam_perjalanan_tab" data-toggle="tab" href="#tab_pesanan_dalam_perjalanan" role="tab" aria-controls="tab_pesanan_dalam_perjalanan" aria-selected="false">Dalam Perjalanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pesanan_belum_diambil_tab" data-toggle="tab" href="#tab_pesanan_belum_diambil" role="tab" aria-controls="tab_pesanan_belum_diambil" aria-selected="false">Belum Diambil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pesanan_belum_dikonfirmasi_pembeli_tab" data-toggle="tab" href="#tab_pesanan_belum_dikonfirmasi_pembeli" role="tab" aria-controls="tab_pesanan_belum_dikonfirmasi_pembeli" aria-selected="false">Belum Dikonfirmasi Pembeli</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pesanan_berhasil_tab" data-toggle="tab" href="#tab_pesanan_berhasil" role="tab" aria-controls="tab_pesanan_berhasil" aria-selected="false">Berhasil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab_pesanan_dibatalkan_tab" data-toggle="tab" href="#tab_pesanan_dibatalkan" role="tab" aria-controls="tab_pesanan_dibatalkan" aria-selected="false">Dibatalkan</a>
            </li>
        </ul>
        <div class="tab-content tab-content-border" id="tab-content-1">
            
            <div class="tab-pane fade show active" id="tab_semua_pesanan" role="tabpanel" aria-labelledby="tab_semua_pesanan_tab">
                @include('user.pembelian.tab_semua_pesanan')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pesanan_belum_bayar" role="tabpanel" aria-labelledby="tab_pembelian_belum_bayar_tab">
                @include('user.pembelian.tab_pesanan_belum_bayar')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pembayaran_belum_dikonfirmasi" role="tabpanel" aria-labelledby="tab_pembayaran_belum_dikonfirmasi_tab">
                @include('user.pembelian.tab_pembayaran_belum_dikonfirmasi')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_sedang_dikemas" role="tabpanel" aria-labelledby="tab_sedang_dikemas_tab">
                @include('user.pembelian.tab_sedang_dikemas')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pesanan_dalam_perjalanan" role="tabpanel" aria-labelledby="tab_pesanan_dalam_perjalanan_tab">
                @include('user.pembelian.tab_pesanan_dalam_perjalanan')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pesanan_belum_diambil" role="tabpanel" aria-labelledby="tab_pesanan_belum_diambil_tab">
                @include('user.pembelian.tab_pesanan_belum_diambil')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pesanan_belum_dikonfirmasi_pembeli" role="tabpanel" aria-labelledby="tab_pesanan_belum_dikonfirmasi_pembeli_tab">
                @include('user.pembelian.tab_pesanan_belum_dikonfirmasi_pembeli')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pesanan_berhasil" role="tabpanel" aria-labelledby="tab_pesanan_berhasil_tab">
                @include('user.pembelian.tab_pesanan_berhasil')
            </div><!-- .End .tab-pane -->
            <div class="tab-pane fade" id="tab_pesanan_dibatalkan" role="tabpanel" aria-labelledby="tab_pesanan_dibatalkan_tab">
                @include('user.pembelian.tab_pesanan_dibatalkan')
            </div><!-- .End .tab-pane -->
            
        </div><!-- End .tab-content -->
    </div><!-- End .col-md-6 -->

</div><!-- End .col-md-6 -->


@endsection

