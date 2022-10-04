@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

<link rel="stylesheet" href="../bootstrap/pesananku_all.css">

@section('container')

<div class="card-header px-4 py-5">
    <h5 class="text-muted mb-0">Daftar Pesanan <span style="color: #800000;">Pelanggan</span></h5>
</div>

@foreach($cek_purchase as $cek_purchase)

@foreach($purchases as $purchase)

@if($purchase->purchase_id == $cek_purchase->purchase_id)

@if($purchase->status_pembelian == "status1")

@else
<div class="card-body p-4">
    <a href="./detail_pembelian/{{$purchase->purchase_id}}" class="p-2 card shadow-0 border mb-1">
        <div class="row d-flex align-items-center">
            <div class="col-md-12 mb-1" align="center">
                @foreach($profiles as $profile)
                    @if($profile->id == $purchase->user_id)
                        <p class="text-muted mb-0"><b>{{$profile->name}}</b></p>
                    @endif
                @endforeach
            </div>
        </div>
        @foreach($product_purchases as $product_purchase)
            @if($product_purchase->purchase_id == $purchase->purchase_id)
            <div class="card border mb-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            @foreach($product_images as $product_image)
                                @if($product_image->product_id == $product_purchase->product_id)
                                    @if($loop->iteration % 3 == 0)
                                    <img src="./asset/u_file/product_image/{{$product_image->product_image_name}}" class="img-fluid" alt="Product image">
                                    @elseif($loop->iteration % 6 == 0)
                                    
                                    @else
                                    
                                    @endif
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                            <p class="text-muted mb-0">{{$product_purchase->product_name}}</p>
                        </div>

                        @foreach($product_specifications as $product_specification)
                            @if($product_specification->product_id == $product_purchase->product_id)
                            <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                <p class="text-muted mb-0 small">{{$product_specification->nama_spesifikasi}}</p>
                            </div>
                            @endif
                        @endforeach

                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                            <p class="text-muted mb-0 small">Jmlh: {{$product_purchase->jumlah_pembelian_produk}}</p>
                        </div>
                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                            <p class="text-muted mb-0 small">
                                <?php
                                    $harga_produk = "Rp " . number_format($product_purchase->price*$product_purchase->jumlah_pembelian_produk,2,',','.');     
                                    echo $harga_produk
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
        <hr class="mb-2" style="background-color: #e0e0e0; opacity: 1;">
        <div class="row d-flex align-items-center">
            @if($purchase->status_pembelian == "status5")
            <div class="col-md-12 mb-1">
                <p class="text-muted ">Jejak Pembelian</p>
            </div>
            <div class="col-md-12">
                <div class="progress" style="height: 6px; border-radius: 16px;">
                <div class="progress-bar" role="progressbar"
                    style="width: 100%; border-radius: 16px; background-color: #800000;" aria-valuenow="65"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-around mb-1">
                    <p class="text-muted mt-1 mb-0 small ms-xl-5">Penjualan Telah Dibayar. PENJUALAN BERHASIL.</p>
                </div>
            </div>
            @endif

            @if($purchase->status_pembelian == "status4")
            <div class="col-md-12 mb-1">
                <p class="text-muted ">Jejak Pembelian</p>
            </div>
            <div class="col-md-12">
                <div class="progress" style="height: 6px; border-radius: 16px;">
                <div class="progress-bar" role="progressbar"
                    style="width: 66.6%; border-radius: 16px; background-color: #800000;" aria-valuenow="65"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-around mb-1">
                    <p class="text-muted mt-1 mb-0 small ms-xl-5">Pengiriman Berhasil. SILAHKAN TUNGGU BAYARAN.</p>
                </div>
            </div>
            @endif

            @if($purchase->status_pembelian == "status3")
            <div class="col-md-12 mb-1">
                <p class="text-muted ">Jejak Pembelian</p>
            </div>
            <div class="col-md-12">
                <div class="progress" style="height: 6px; border-radius: 16px;">
                <div class="progress-bar" role="progressbar"
                    style="width: 33.3%; border-radius: 16px; background-color: #800000;" aria-valuenow="65"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-around mb-1">
                    <p class="text-muted mt-1 mb-0 small ms-xl-5">Pesanan Sedang Dalam Perjalanan.  TUNGGU PESANAN DITERIMA.</p>
                </div>
            </div>
            @endif

            @if($purchase->status_pembelian == "status2")
            <div class="col-md-12 mb-1">
                <p class="text-muted ">Jejak Pembelian</p>
            </div>
            <div class="col-md-12">
                <div class="progress" style="height: 6px; border-radius: 16px;">
                <div class="progress-bar" role="progressbar"
                    style="width: 0%; border-radius: 16px; background-color: #800000;" aria-valuenow="65"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-around mb-1">
                    <p class="text-muted mt-1 mb-0 small ms-xl-5">Ada Pesanan. SILAHKAN PROSES PESANAN.</p>
                </div>
            </div>
            @endif

            @if($purchase->status_pembelian == "status1")
            
            @endif
        </div>
    </a>
</div>

@endif

@endif

@endforeach

@endforeach

@endsection

