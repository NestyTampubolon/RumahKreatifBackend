@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('container')

@if($cek_verifikasi)
<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <p>Menunggu akun anda diverifikasi.</p>
</div><!-- .End .tab-pane -->

@else
<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <p>Akun anda belum terverifikasi</p>
    <a class="nav-link btn btn-outline-primary-2" href="./verifikasi">
        <span>ISI DATA</span>
        <i class="icon-long-arrow-right"></i>
    </a>
</div><!-- .End .tab-pane -->

@endif

@endsection

