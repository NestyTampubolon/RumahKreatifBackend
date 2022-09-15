@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <p>Tambahkan produk anda</p>
    <a class="nav-link btn btn-outline-primary-2" href="./tambah_produk/pilih_kategori">
        <span>TAMBAH PRODUK</span>
        <i class="icon-long-arrow-right"></i>
    </a>
</div><!-- .End .tab-pane -->

@endsection

