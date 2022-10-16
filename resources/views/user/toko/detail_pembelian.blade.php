@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')

@if($purchases->status_pembelian == "status1")

@else
<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        @if($purchases->status_pembelian == "status5" || $purchases->status_pembelian == "status5_ambil")
                            <p class="">Penjualan Telah Dibayar. PENJUALAN BERHASIL.</p>
                        @endif

                        @if($purchases->status_pembelian == "status4")
                            <p class="">Pengiriman Berhasil. SILAHKAN TUNGGU BAYARAN.</p>
                        @endif
                        
                        @if($purchases->status_pembelian == "status4_ambil_a")
                            <p class="">TUNGGU PELANGGAN MENGKONFIRMASI PESANAN YANG TELAH DIAMBIL.</p>
                        @endif
                         
                        @if($purchases->status_pembelian == "status4_ambil_b")
                            <?php
                                $total_harga = "Rp." . number_format(floor((int)$total_harga->total_harga),2,',','.');
                            ?>
                            <p class="">Pengiriman Berhasil. SILAHKAN TUNGGU BAYARAN SENILAI 
                            <b><a id="total_harga_produk">{{$total_harga}}</a></b>    
                            DIKIRIM KE REKENING YANG TELAH TOKO DAFTARKAN.</p>
                        @endif

                        @if($purchases->status_pembelian == "status3")
                            <p class="">Pesanan Sedang Dalam Perjalanan. TUNGGU PESANAN DITERIMA.</p>
                        @endif
                        
                        @if($purchases->status_pembelian == "status3_ambil")
                            <p class="">SILAHKAN TUNGGU PELANGGAN MENGAMBIL PESANAN.</p>
                        @endif

                        @if($purchases->status_pembelian == "status2" || $purchases->status_pembelian == "status2_ambil")
                            <p class="">Ada Pesanan. SILAHKAN PROSES PESANAN.</p>
                        @endif

                        @if($purchases->status_pembelian == "status1" || $purchases->status_pembelian == "status1")
                        
                        @endif
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </div><!-- End .row -->

        @if($purchases->status_pembelian == "status2" || $purchases->status_pembelian == "status2_ambil"
        || $purchases->status_pembelian == "status3_ambil" || $purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status4_ambil_b")
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        @if($purchases->status_pembelian == "status2")
                        <p class="">Jika pesanan telah dikirim dan sedang dalam perjalanan. SILAHKAN KONFIRMASI PESANAN.</p>
                        @endif
                        
                        @if($purchases->status_pembelian == "status2_ambil")
                        <p class="">Jika pesanan telah disiapkan. SILAHKAN KONFIRMASI PESANAN.</p>
                        @endif
                        
                        @if($purchases->status_pembelian == "status3_ambil")
                        <p class="">Jika pesanan telah diambil pelanggan. SILAHKAN KONFIRMASI PESANAN.</p>
                        @endif
                        
                        @if($purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status4_ambil_b")
                        <p class="">Jika bayaran telah diterima. SILAHKAN KONFIRMASI.</p>
                        @endif

                        <a href="../update_status_pembelian/{{$purchases->purchase_id}}" class="btn btn-primary btn-round">
                            <span>KONFIRMASI</span>
                        </a>
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </div><!-- End .row -->
        @endif
        
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body" align="center">
                        @if($profile->id == $purchases->user_id)
                            <p class=""><b>{{$profile->name}}</b></p>
                        @endif
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </div><!-- End .row -->
        
        @if($purchases->status_pembelian == "status2" || $purchases->status_pembelian == "status3" 
        || $purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status5" )
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <h6 class="">Alamat Pengiriman : <br><br> {{$purchases->alamat_purchase}}</h6>
                        <p></p>
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </div><!-- End .row -->

        @else

        @endif

    <div class="row">
    @foreach($product_purchases as $product_purchases)
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <a href="../edit_produk/{{$product_purchases->product_id}}"><h3 class="card-title">{{$product_purchases->product_name}}</h3></a>
                    <p>
                        <?php
                            $jumlah_product_specifications = DB::table('product_specifications')->where('product_id', $product_purchases->product_id)->count();
                        ?>
                        @if($jumlah_product_specifications == 0)

                        @else
                            @foreach($product_specifications as $product_specification)
                                @if($product_specification->product_id == $product_purchases->product_id)
                                    <a>{{$product_specification->nama_spesifikasi}},</a>&nbsp;
                                @endif
                            @endforeach
                        @endif
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
    @endforeach
    </div><!-- End .row -->
</div><!-- .End .tab-pane -->

@endif

@endsection

