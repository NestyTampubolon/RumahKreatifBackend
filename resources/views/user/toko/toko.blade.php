@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">

    @foreach($merchants as $merchants)
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">Profil Toko</h3><!-- End .card-title -->

                    <p>{{$merchants->nama_merchant}}</p>
                    <p>{{$merchants->deskripsi_toko}}</p>
                    <p>{{$merchants->kontak_toko}}</p>
                    <a href="#">Edit <i class="icon-edit"></i></a></p>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    </div><!-- End .row -->
    @endforeach
</div><!-- .End .tab-pane -->

@endsection

