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
        </ul>
        <div class="tab-content tab-content-border" id="tab-content-1">
            
            <div class="tab-pane fade show active" id="tab_semua_pembelian" role="tabpanel" aria-labelledby="tab_semua_pembelian_tab">
                @include('user.toko.tab_semua_pembelian')
            </div><!-- .End .tab-pane -->
        </div><!-- End .tab-content -->
    </div><!-- End .col-md-6 -->

</div><!-- End .col-md-6 -->

@endsection

