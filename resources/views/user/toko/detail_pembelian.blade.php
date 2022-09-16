@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">

    @foreach($product_purchases as $product_purchases)
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">{{$product_purchases->product_name}}</h3><!-- End .card-title -->
                    <p>
                        @foreach($purchase_product_specifications as $purchase_product_specification)
                            @if($purchase_product_specification->product_purchase_id == $product_purchases->product_purchase_id)
                                <a>{{$purchase_product_specification->nama_spesifikasi}},</a>&nbsp;
                            @endif
                        @endforeach
                    </p>
                    <p>Hrg: {{$product_purchases->jumlah_pembelian_produk}}</p>
                    <p>
                        <?php
                            $harga_produk = "Rp " . number_format($product_purchases->price*$product_purchases->jumlah_pembelian_produk,2,',','.');     
                            echo $harga_produk
                        ?>
                    </p>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    </div><!-- End .row -->
    @endforeach
</div><!-- .End .tab-pane -->

@endsection

