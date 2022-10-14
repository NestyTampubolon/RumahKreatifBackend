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
            <a href="./detail_pembelian/{{$purchase->purchase_id}}" class="p-2 card shadow-0 border mb-1">
                @foreach($product_purchases as $product_purchase)
                    @if($product_purchase->purchase_id == $purchase->purchase_id)
                    <div class="card border mb-1">
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
                                    $total_harga_semula = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_semula'))
                                    ->where('product_purchases.purchase_id', $purchase->purchase_id)
                                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')->first();
                                ?>
                                @if($jumlah_claim_voucher == 0)
                                    <?php
                                        $total_harga_semula = "Rp." . number_format(floor($total_harga_semula->total_harga_semula),2,',','.');
                                        $total_harga_pembelian = $product_purchase->price * $product_purchase->jumlah_pembelian_produk;
                                        $total_harga_pembelian_fix = "Rp." . number_format(floor($total_harga_pembelian),2,',','.');
                                    ?>
                                @else
                                    @foreach($claim_vouchers as $claim_voucher)
                                        @if($claim_voucher->checkout_id == $checkout->checkout_id)
                                            <?php
                                                $jumlah_pembelian_checkout = DB::table('purchases')->where('checkout_id', $purchase->checkout_id)->count();
                                                $jumlah_product_purchase_checkout = DB::table('product_purchases')
                                                ->where('purchases.checkout_id', $purchase->checkout_id)
                                                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                                                ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->count();
                                                $jumlah_product_purchase = DB::table('product_purchases')->where('purchase_id', $purchase->purchase_id)->count();
                                                
                                                $total_harga = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga'))
                                                ->where('purchases.checkout_id', $purchase->checkout_id)
                                                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                                                ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();
                                                
                                                $voucher = DB::table('vouchers')->where('voucher_id', $claim_voucher->voucher_id)->first();
                                                
                                                $total_harga_pembelian = $product_purchase->price * $product_purchase->jumlah_pembelian_produk;

                                                $potongan = $total_harga_pembelian * $voucher->potongan / 100 / $jumlah_product_purchase;
                                                $checkout_hasil_potong = $total_harga->total_harga * $voucher->potongan / 100;

                                                if($checkout_hasil_potong > $voucher->maksimal_pemotongan){
                                                    $potongan = $voucher->maksimal_pemotongan / $jumlah_pembelian_checkout / $jumlah_product_purchase;
                                                }
                                        
                                                $total_harga_fix = (int)$total_harga_pembelian - $potongan;
                                        
                                                $total_harga_pembelian_fix = "Rp." . number_format(floor($total_harga_fix),2,',','.');
                                            ?>
                                        @endif
                                    @endforeach
                                @endif

                                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                        {{$total_harga_pembelian_fix}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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
            </a>
        </div>
        @endif
    @endforeach
@endforeach
    
@else

<div class="col-md-12" align="center">
    <h6 style="color:darkred"><b>Anda Tidak Memiliki Pesanan</b></h6>
</div>

@endif

@endsection

