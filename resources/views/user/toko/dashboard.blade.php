@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Toko')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-dashboard-link">
    <div class="row">

        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="text-muted mb-0"><span style="color: #800000;">Jumlah Transaksi</span></h5>
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            <div class="mb-2"></div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-dashboard">
                        <div class="card-body" align="center" style="padding: 20px 0px 0px 0px">
                            <a style="font-size:15px; font-weight: bold;">Pesanan Sedang Berlangsung</a><br>
                            <a style="font-size:25px;">{{$jumlah_pesanan_sedang_berlangsung}}</a>
                        </div><!-- End .card-body -->
                    </div><!-- End .card-dashboard -->
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-dashboard">
                        <div class="card-body" align="center" style="padding: 20px 0px 0px 0px">
                            <a style="font-size:15px; font-weight: bold;">Pesanan Berhasil [Belum Konfirmasi Pembayaran]</a><br>
                            <a style="font-size:25px;">{{$jumlah_pesanan_berhasil_belum_dibayar}}</a>
                        </div><!-- End .card-body -->
                    </div><!-- End .card-dashboard -->
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-dashboard">
                        <div class="card-body" align="center" style="padding: 20px 0px 0px 0px">
                            <a style="font-size:15px; font-weight: bold;">Pesanan Berhasil [Telah Konfirmasi Pembayaran]</a><br>
                            <a style="font-size:25px;">{{$jumlah_pesanan_berhasil_telah_dibayar}}</a>
                        </div><!-- End .card-body -->
                    </div><!-- End .card-dashboard -->
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->
            
        </div><!-- .End .tab-pane -->

        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="text-muted mb-0"><span style="color: #800000;">Pemberitahuan</span></h5>
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->
            <div class="mb-2"></div>
            @foreach($cek_purchases as $cek_purchase)
                @foreach($purchases as $purchase)
                    @if($purchase->purchase_id == $cek_purchase->purchase_id)
                        <?php
                            $kurir = "";

                            if($purchase->courier_code == "pos"){ $kurir = "POS Indonesia (POS)"; }
                            
                            else if($purchase->courier_code == "jene"){ $kurir = "Jalur Nugraha Eka (JNE)"; }
                        ?>
                        @if($purchase->status_pembelian == "status2")
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-dashboard">
                                        <div class="card-body">
                                            <h3 class="card-title">Pesanan masuk!</h3>
                                            <p>Anda memiliki pesanan dengan kode pembelian <b>{{$purchase->kode_pembelian}}</b>. Silahkan kirim pesanan melalui <b>{{$kurir}} | {{$purchase->service}}</b>. Kemudian masukkan kirim resi pengiriman.</p>
                                            <h4 style="float:right; margin-top: -5px; margin-bottom: -5px"><a href="./detail_pembelian/{{$purchase->purchase_id}}"><i class="icon-long-arrow-right"></i></a></h4>
                                        </div><!-- End .card-body -->
                                    </div><!-- End .card-dashboard -->
                                </div><!-- End .col-lg-6 -->
                            </div><!-- End .row -->
                        @endif
                        @if($purchase->status_pembelian == "status2_ambil")
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-dashboard">
                                        <div class="card-body">
                                            <h3 class="card-title">Pesanan masuk!</h3>
                                            <p>Anda memiliki pesanan dengan kode pembelian <b>{{$purchase->kode_pembelian}}</b>. Silahkan kemas agar pembeli dapat mengambil pesanan. Kemudian konfirmasi bahwa pesanan sudah dikemas.</p>
                                            <h4 style="float:right; margin-top: -5px; margin-bottom: -5px"><a href="./detail_pembelian/{{$purchase->purchase_id}}"><i class="icon-long-arrow-right"></i></a></h4>
                                        </div><!-- End .card-body -->
                                    </div><!-- End .card-dashboard -->
                                </div><!-- End .col-lg-6 -->
                            </div><!-- End .row -->
                        @endif
                        @if($purchase->status_pembelian == "status3")
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-dashboard">
                                        <div class="card-body">
                                            <h3 class="card-title">Pesanan sedang dalam perjalanan!</h3>
                                            <p>Pesanan dengan kode pembelian <b>{{$purchase->kode_pembelian}}</b> sedang dalam perjalanan. Silahkan tunggu pesanan sampai tujuan. Silahkan tunggu pembeli bahwa pesanan telah sampai.</p>
                                            <h4 style="float:right; margin-top: -5px; margin-bottom: -5px"><a href="./detail_pembelian/{{$purchase->purchase_id}}"><i class="icon-long-arrow-right"></i></a></h4>
                                        </div><!-- End .card-body -->
                                    </div><!-- End .card-dashboard -->
                                </div><!-- End .col-lg-6 -->
                            </div><!-- End .row -->
                        @endif
                        @if($purchase->status_pembelian == "status3_ambil")
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-dashboard">
                                        <div class="card-body">
                                            <h3 class="card-title">Pesanan belum diambil!</h3>
                                            <p>Silahkan tunggu pembeli mengambil pesanan dengan kode pembelian <b>{{$purchase->kode_pembelian}}</b>. Kemudian konfirmasi jika pesanan telah diambil.</p>
                                            <h4 style="float:right; margin-top: -5px; margin-bottom: -5px"><a href="./detail_pembelian/{{$purchase->purchase_id}}"><i class="icon-long-arrow-right"></i></a></h4>
                                        </div><!-- End .card-body -->
                                    </div><!-- End .card-dashboard -->
                                </div><!-- End .col-lg-6 -->
                            </div><!-- End .row -->
                        @endif
                        @if($purchase->status_pembelian == "status4_ambil_a")
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-dashboard">
                                        <div class="card-body">
                                            <h3 class="card-title">Pesanan telah diambil!</h3>
                                            <p>Silahkan tunggu konfirmasi bahwa pembeli telah mengambil pesanan dengan kode pembelian <b>{{$purchase->kode_pembelian}}</b></p>
                                            <h4 style="float:right; margin-top: -5px; margin-bottom: -5px"><a href="./detail_pembelian/{{$purchase->purchase_id}}"><i class="icon-long-arrow-right"></i></a></h4>
                                        </div><!-- End .card-body -->
                                    </div><!-- End .card-dashboard -->
                                </div><!-- End .col-lg-6 -->
                            </div><!-- End .row -->
                        @endif
                        @if($purchase->status_pembelian == "status4" || $purchase->status_pembelian == "status4_ambil_b")
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-dashboard">
                                        <div class="card-body">
                                            <h3 class="card-title">Pesanan Berhasil [Belum Konfirmasi Pembayaran]</h3>
                                            <p>Silahkan tunggu pembayaran dengan kode pembelian <b>{{$purchase->kode_pembelian}}</b> telah diterima. Kemudian silahkan konfirmasi jika telah dibayar.</p>
                                            <h4 style="float:right; margin-top: -5px; margin-bottom: -5px"><a href="./detail_pembelian/{{$purchase->purchase_id}}"><i class="icon-long-arrow-right"></i></a></h4>
                                        </div><!-- End .card-body -->
                                    </div><!-- End .card-dashboard -->
                                </div><!-- End .col-lg-6 -->
                            </div><!-- End .row -->
                        @endif
                    @endif
                <?php
                    $count_status2 = DB::table('product_purchases')->where('merchant_id', $toko)->where('is_cancelled', 0)->where('status_pembelian', 'status2')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->count();
                    $count_status2_ambil = DB::table('product_purchases')->where('merchant_id', $toko)->where('is_cancelled', 0)->where('status_pembelian', 'status2_ambil')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->count();
                    $count_status3 = DB::table('product_purchases')->where('merchant_id', $toko)->where('is_cancelled', 0)->where('status_pembelian', 'status3')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->count();
                    $count_status3_ambil = DB::table('product_purchases')->where('merchant_id', $toko)->where('is_cancelled', 0)->where('status_pembelian', 'status3_ambil')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->count();
                    $count_status4_ambil_a = DB::table('product_purchases')->where('merchant_id', $toko)->where('is_cancelled', 0)->where('status_pembelian', 'status4_ambil_a')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->count();
                    $jumlah_pesanan_sedang_berlangsung = $count_status2 + $count_status2_ambil + $count_status3 + $count_status3_ambil + $count_status4_ambil_a;

                    $count_status4 = DB::table('product_purchases')->where('merchant_id', $toko)->where('is_cancelled', 0)->where('status_pembelian', 'status4')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->count();
                    $count_status4_ambil_b = DB::table('product_purchases')->where('merchant_id', $toko)->where('is_cancelled', 0)->where('status_pembelian', 'status4_ambil_b')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->count();
                    $jumlah_pesanan_berhasil_belum_dibayar = $count_status4 + $count_status4_ambil_b;

                    $count_status5 = DB::table('product_purchases')->where('merchant_id', $toko)->where('is_cancelled', 0)->where('status_pembelian', 'status5')
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->count();
                    $jumlah_pesanan_berhasil_telah_dibayar = $count_status5;
                ?>

                @endforeach
            @endforeach
        </div><!-- .End .tab-pane -->

    </div><!-- End .row -->
</div><!-- .End .tab-pane -->

@endsection