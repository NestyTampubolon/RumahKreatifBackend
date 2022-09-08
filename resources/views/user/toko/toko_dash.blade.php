@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('container')

@if($cek_verified && $cek_rekening)
<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
<form action="./MasukToko" method="post" enctype="multipart/form-data">
    @csrf
        <label>Password *</label>
        <input type="password" name="password" class="form-control" required>
        <small class="form-text">Pastikan password yang anda masukkan benar.</small>

        <button type="submit" class="btn btn-primary btn-round">
            <span>MASUK</span>
        </button>
    </form>
</div><!-- .End .tab-pane -->

@elseif($cek_verified)
<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <p>Akun anda telah diverifikasi.</p>
    <p>Lanjutkan dengan mengisi data rekening anda.</p>
    <a class="nav-link btn btn-outline-primary-2" href="./rekening">
        <span>ISI DATA</span>
        <i class="icon-long-arrow-right"></i>
    </a>
</div><!-- .End .tab-pane -->

@elseif($cek_verifikasi)
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

