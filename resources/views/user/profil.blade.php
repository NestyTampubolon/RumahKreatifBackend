@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">Profil Pengguna</h3><!-- End .card-title -->
                    <p>{{$profile->name}}</p>
                    @if($profile->gender == "L")
                        <p>Laki-laki</p>
                    @elseif($profile->gender == "P")
                        <p>Perempuan</p>
                    @endif
                    <p>{{$profile->birthday}}</p>
                    <p>{{$profile->no_hp}}</p>
                    <a href="./edit_profil">Edit <i class="icon-edit"></i></a></p>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">Profil Akun</h3><!-- End .card-title -->
                    <p>{{$profile->username}}</p>
                    <p>{{$profile->email}}</p>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    </div><!-- End .row -->
</div><!-- .End .tab-pane -->

@endsection

