@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">Profil Pengguna</h3><!-- End .card-title -->
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td> : </td>
                            <td>{{$profile->name}}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td> : </td>
                            <td>
                                @if($profile->gender == "L")
                                    Laki-laki
                                @elseif($profile->gender == "P")
                                    Perempuan
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td> : </td>
                            <td>{{$profile->birthday}}</td>
                        </tr>
                        <tr>
                            <td>Nomor Handphone</td>
                            <td> : </td>
                            <td>{{$profile->no_hp}}</td>
                        </tr>
                    </table>
                    <a href="./edit_profil">Edit <i class="icon-edit"></i></a></p>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">Profil Akun</h3><!-- End .card-title -->
                    <table>
                        <tr>
                            <td>Username</td>
                            <td> : </td>
                            <td>{{$profile->username}}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td> : </td>
                            <td>{{$profile->email}}</td>
                        </tr>
                    </table>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    </div><!-- End .row -->
</div><!-- .End .tab-pane -->

@endsection

