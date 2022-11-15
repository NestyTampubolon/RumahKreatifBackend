@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

<link rel="stylesheet" href="../bootstrap/pesananku_all.css">

@section('container')

<div class="card-header px-4 py-5">
    <h5 class="text-muted mb-0">Pesanan Anda, <span style="color: #800000;">{{$profile->name}}</span>!</h5>
</div>

@if($cek_purchases)

@foreach($checkouts as $checkout)
    @foreach($purchases as $purchase)
        @if($purchase->checkout_id == $checkout->checkout_id)
        <div class="card-body p-4">
            <div class="p-2 card shadow-0 border mb-1">
                <div class="col-md-12 d-flex justify-content-around" style="margin: 10px 0px -30px 0px">
                    <h5>{{$purchase->kode_pembelian}}</h5>
                </div>
                <hr class="mb-2" style="background-color: #e0e0e0; opacity: 1;">
                @foreach($product_purchases as $product_purchase)
                    @if($product_purchase->purchase_id == $purchase->purchase_id)
                    <a href="./detail_pembelian/{{$purchase->purchase_id}}" class="card border mb-1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php
                                        $product_images = DB::table('product_images')->select('product_image_name')->where('product_id', $product_purchase->product_id)->orderBy('product_image_id', 'asc')->limit(1)->get();
                                    ?>
                                    @foreach($product_images as $product_image)
                                        <img src="./asset/u_file/product_image/{{$product_image->product_image_name}}" class="img-fluid" alt="{{$product_purchase->product_name}}">
                                    @endforeach
                                </div>
                                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                    <p class="text-muted mb-0">{{$product_purchase->product_name}}</p>
                                </div>
                                <?php
                                    $jumlah_product_specifications = DB::table('product_specifications')->where('product_id', $product_purchase->product_id)->count();
                                ?>
                                @if($jumlah_product_specifications == 0)

                                @else
                                    @foreach($product_specifications as $product_specification)
                                        @if($product_specification->product_id == $product_purchase->product_id)
                                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                            <p class="text-muted mb-0 small">{{$product_specification->nama_spesifikasi}}</p>
                                        </div>
                                        @endif
                                    @endforeach
                                @endif

                                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                    <p class="text-muted mb-0 small">Jmlh: {{$product_purchase->jumlah_pembelian_produk}}</p>
                                </div>
                                
                                <?php
                                    $jumlah_claim_voucher = DB::table('claim_vouchers')->where('checkout_id', $purchase->checkout_id)->count();
                                        
                                    $total_harga_pembelian = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
                                    ->where('purchases.checkout_id', $purchase->checkout_id)
                                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                                    ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();

                                    // $total_harga_pembelian = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
                                    // ->where('purchases.checkout_id', $purchase->checkout_id)
                                    // ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                    // ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                                    // ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();
                                    
                                    $total_harga_pembelian_perproduk = $product_purchase->price * $product_purchase->jumlah_pembelian_produk;
                                    
                                    $jumlah_product_purchase = DB::table('product_purchases')->where('purchase_id', $purchase->purchase_id)->count();

                                    $cek_target_kategori = 0;
                                ?>
                                @if($jumlah_claim_voucher == 0)
                                    <?php
                                        $total_harga_pembelian_produk = $total_harga_pembelian_perproduk;
                                        $total_harga_pembelian_produk_fix = "Rp." . number_format(floor($total_harga_pembelian_produk),0,',','.');
                                    ?>
                                @else
                                    @foreach($claim_vouchers as $claim_voucher)
                                        @if($claim_voucher->checkout_id == $checkout->checkout_id)
                                            <?php
                                                $target_kategori = explode(",", $claim_voucher->target_kategori);

                                                foreach($target_kategori as $target_kategori){
                                                    
                                                    $subtotal_harga_produk = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
                                                    ->where('purchases.checkout_id', $purchase->checkout_id)->where('category_id', $target_kategori)
                                                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                                                    ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();
                                
                                                    $potongan_subtotal = [];
                                                    $potongan_subtotal[] = (int)$subtotal_harga_produk->total_harga_pembelian * $claim_voucher->potongan / 100;
                                                    

                                                    $potongan_subtotal_perproduk = (int)$total_harga_pembelian_perproduk * $claim_voucher->potongan / 100;
                                
                                                    $jumlah_potongan_subtotal = array_sum($potongan_subtotal);

                                                    if($jumlah_potongan_subtotal <= $claim_voucher->maksimal_pemotongan){
                                                        if($product_purchase->category_id == $target_kategori){
                                                            $potongan_harga_barang = $potongan_subtotal_perproduk;
                                                        }
    
                                                        else{
                                                            $potongan_harga_barang = 0;
                                                        }
                                                    }
    
                                                    else if($jumlah_potongan_subtotal > $claim_voucher->maksimal_pemotongan){
                                                        if($product_purchase->category_id == $target_kategori){
                                                            $potongan_harga_barang = $total_harga_pembelian_perproduk / $subtotal_harga_produk->total_harga_pembelian * $claim_voucher->maksimal_pemotongan;
                                                        }
    
                                                        else{
                                                            $potongan_harga_barang = 0;
                                                        }
                                                    }
                                                    
                                                    if($claim_voucher->tipe_voucher == "pembelian"){
                                                        $total_harga_pembelian_produk = (int)$total_harga_pembelian_perproduk - $potongan_harga_barang;
                                                        $total_harga_pembelian_produk_fix = "Rp." . number_format(floor($total_harga_pembelian_produk),0,',','.');
                                                    }
                                            ?>
                                            
                                            @if($target_kategori == $product_purchase->category_id)
                                            <?php $cek_target_kategori = $product_purchase->category_id; ?>
                                            <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                {{$total_harga_pembelian_produk_fix}}
                                            </div>
                                            
                                            @elseif($target_kategori != $product_purchase->category_id)

                                            @endif

                                            <?php
                                                        
                                                }
                                            ?>
                                        @endif
                                    @endforeach
                                @endif

                                @if($product_purchase->category_id != $cek_target_kategori)
                                    <?php
                                        $total_harga_pembelian_produk_no_potongan = $total_harga_pembelian_perproduk;
                                        $total_harga_pembelian_produk_no_potongan_fix = "Rp." . number_format(floor($total_harga_pembelian_produk_no_potongan),0,',','.');
                                    ?>
                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        {{$total_harga_pembelian_produk_no_potongan_fix}}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </a>
                    @endif
                @endforeach
                <hr class="mb-2" style="background-color: #e0e0e0; opacity: 1;">
                <div class="row d-flex align-items-center">
                    @if($purchase->status_pembelian == "status4" || $purchase->status_pembelian == "status4_ambil_b"
                    || $purchase->status_pembelian == "status5" || $purchase->status_pembelian == "status5_ambil")
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
                                <p class="text-muted mt-1 mb-0 small ms-xl-5">Pesanan Diterima. PEMBELIAN BERHASIL.</p>
                            </div>
                        </div>
                    @endif

                    @if($purchase->status_pembelian == "status3" || $purchase->status_pembelian == "status3_ambil"
                    || $purchase->status_pembelian == "status4_ambil_a")
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
                                @if($purchase->status_pembelian == "status3")
                                <p class="text-muted mt-1 mb-0 small ms-xl-5">Pesanan Sedang Dalam Perjalanan. TUNGGU PESANAN SAMPAI.</p>
                                @endif

                                @if($purchase->status_pembelian == "status3_ambil")
                                    <p class="text-muted mt-1 mb-0 small ms-xl-5">SILAHKAN AMBIL PESANAN ANDA DI TOKO.</p>
                                @endif
                                
                                @if($purchase->status_pembelian == "status4_ambil_a")
                                    <p class="text-muted mt-1 mb-0 small ms-xl-5">Pesanan telah diberikan.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($purchase->status_pembelian == "status2" || $purchase->status_pembelian == "status2_ambil")
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
                                <p class="text-muted mt-1 mb-0 small ms-xl-5">Pesanan Sedang Diproses. TUNGGU PESANAN DIPROSES.</p>
                            </div>
                        </div>
                    @endif

                    @if($purchase->status_pembelian == "status1" || $purchase->status_pembelian == "status1_ambil")
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
                            @if($count_proof_of_payment->count_proof_of_payment == 0)
                                <p class="text-muted mt-1 mb-0 small ms-xl-5">Belum dapat dikonfirmasi. KIRIM BUKTI PEMBAYARAN.</p>
                            @endif
                            
                            <?php
                                $proof_of_payments = DB::table('proof_of_payments')->where('purchase_id', $purchase->purchase_id)->first();
                            ?>
                            @if($count_proof_of_payment->count_proof_of_payment != 0)
                                @if($proof_of_payments)
                                    <p class="text-muted mt-1 mb-0 small ms-xl-5">Bukti pembayaran telah dikirim. MENUNGGU KONFIRMASI.</p>
                                @else
                                    <p class="text-muted mt-1 mb-0 small ms-xl-5">Belum dapat dikonfirmasi. KIRIM BUKTI PEMBAYARAN.</p>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            
                <hr class="mb-2" style="background-color: #e0e0e0; opacity: 1;">
                <div class="row d-flex align-items-center">
                    <div class="col-md-12 mb-2">
                        <a class="btn btn-primary btn-round" href="./detail_pembelian/{{$purchase->purchase_id}}">
                            <span>LANJUTKAN</span>
                            <i class="icon-long-arrow-right"></i>
                        </a>
                        @if($purchase->status_pembelian == "status1" || $purchase->status_pembelian == "status1_ambil")
                            @if($count_proof_of_payment->count_proof_of_payment != 0)
                                @if($proof_of_payments)
                                
                                @else
                                <a href="#batalkan_pembelian_{{$purchase->purchase_id}}" class="btn btn-outline-dark btn-rounded" data-toggle="modal" href="" style="float:right">
                                    <span>BATALKAN</span>
                                </a>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
        @endif


        <div class="modal fade" id="batalkan_pembelian_{{$purchase->purchase_id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon-close"></i></span>
                        </button>

                        <div class="form-box">
                            <div class="tab-content" id="tab-content-5">
                                <div class="tab-pane fade show active">
                                    
                                    <label for="isi_review">Apakah anda ingin membatalkan pesanan anda? *</label><br>
                                    <button type="submit" class="btn btn-outline-primary-2 btn-round" data-dismiss="modal" aria-label="Close">
                                        <span>TIDAK</span>
                                    </button>
                                    <button onclick="window.location.href='./batalkan_pembelian/{{$purchase->purchase_id}}'" class="btn btn-primary btn-round" style="float:right">
                                        <span>KONFIRMASI</span>
                                    </button>
                                </div><!-- .End .tab-pane -->
                            </div><!-- End .tab-content -->
                        </div><!-- End .form-box -->
                    </div><!-- End .modal-body -->
                </div><!-- End .modal-content -->
            </div><!-- End .modal-dialog -->
        </div><!-- End .modal -->

    @endforeach
@endforeach
    
@else

<div class="col-md-12" align="center">
    <h6 style="color:darkred"><b>Anda Tidak Memiliki Pesanan. <a href="./produk">Ayo Belanja.</a></b></h6>
</div>

@endif

@endsection

