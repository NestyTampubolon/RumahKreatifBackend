@extends('user/toko/layout/main')

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
                    <h3 class="card-title">Profil Toko</h3><!-- End .card-title -->
                    <table>
                        <tr>
                            <td>Nama Toko : &emsp;</td>
                            <td>{{$merchants->nama_merchant}}</td>
                        </tr>
                        <tr>
                            <td>Deskripsi Toko : &emsp;</td>
                            <td>{{$merchants->deskripsi_toko}}</td>
                        </tr>
                        <tr>
                            <td>Kontak Toko : &emsp;</td>
                            <td>{{$merchants->kontak_toko}}</td>
                        </tr>
                    </table>
                    <a href="./edit_toko">Edit <i class="icon-edit"></i></a></p>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <img src="./asset/u_file/foto_merchant/{{$merchants->foto_merchant}}" alt="{{$merchants->nama_merchant}}" class="product-image">
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    </div><!-- End .row -->
</div><!-- .End .tab-pane -->

@endsection

