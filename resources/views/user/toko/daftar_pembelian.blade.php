@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

<link rel="stylesheet" href="../bootstrap/pesananku_all.css">

@section('container')

<div class="card-header px-4 py-5">
    <h5 class="text-muted mb-0">Daftar Pesanan <span style="color: #800000;">Pelanggan</span></h5>
</div>

@foreach($product_purchases as $product_purchase)

<a href="./detail_pembelian/{{$product_purchase->product_purchase_id}}">
    <div class="card-body p-4">
        <div class="card shadow-0 border mb-1">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <img src="./asset/u_file/product_image/{{$product_purchase->product_image}}"
                        class="img-fluid" alt="Phone">
                    </div>
                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                        <p class="text-muted mb-0">{{$product_purchase->product_name}}</p>
                    </div>

                    @foreach($purchase_product_specifications as $purchase_product_specification)
                        @if($purchase_product_specification->product_purchase_id == $product_purchase->product_purchase_id)
                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                            <p class="text-muted mb-0 small">{{$purchase_product_specification->nama_spesifikasi}}</p>
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
                <!-- <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;">
                <div class="row d-flex align-items-center">
                    <div class="col-md-2">
                        <p class="text-muted mb-0 small">Track Order</p>
                    </div>
                    <div class="col-md-10">
                        <div class="progress" style="height: 6px; border-radius: 16px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: 65%; border-radius: 16px; background-color: #800000;" aria-valuenow="65"
                            aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-around mb-1">
                        <p class="text-muted mt-1 mb-0 small ms-xl-5">Out for delivary</p>
                        <p class="text-muted mt-1 mb-0 small ms-xl-5">Delivered</p>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</a>
    
@endforeach

@endsection

