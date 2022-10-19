@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

<style>
    .fileUpload {
        position: relative;
        overflow: hidden;
        padding-bottom: 5px;
    }
    
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        width:200%;
    }
</style>

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    
    
    @if($purchases->status_pembelian == "status1" || $purchases->status_pembelian == "status1_ambil")
        @if(!$cek_proof_of_payment)
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <p class="">
                            SILAHKAN LAKUKAN PEMBAYARAN PESANAN ANDA SENILAI
                            <b><a id="total_harga_produk"></a></b>
                            KE NOMOR REKENING DIBAWAH INI.<br>
                            <!-- <center><b>081375215693 (DANA)</b> A/N <b>Riyanthi A Sianturi</b><center> -->
                                
                            <center><b>7780086305 (BCA)</b> A/N <b>Timothy J F Henan</b><center>
                        </p>
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </div><!-- End .row -->
        @elseif($cek_proof_of_payment)

        @endif
    @endif

    @if($purchases->status_pembelian == "status1" || $purchases->status_pembelian == "status1_ambil" || $purchases->status_pembelian == "status2"
    || $purchases->status_pembelian == "status2_ambil" || $purchases->status_pembelian == "status3" || $purchases->status_pembelian == "status3_ambil"
    || $purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status4_ambil_a" || $purchases->status_pembelian == "status4_ambil_b"
    || $purchases->status_pembelian == "status5")
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-dashboard">
                <div class="card-body">
                    @if($purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status4_ambil_b"
                    || $purchases->status_pembelian == "status5" || $purchases->status_pembelian == "status5_ambil")
                        <p class="">Pesanan Diterima. PEMBELIAN BERHASIL.</p>
                    @endif
                    
                    @if($purchases->status_pembelian == "status4_ambil_a")
                        <p class="">Pesanan telah diberikan.</p>
                    @endif

                    @if($purchases->status_pembelian == "status3")
                        <p class="">Pesanan Sedang Dalam Perjalanan. TUNGGU PESANAN SAMPAI.</p>
                    @endif
                    
                    @if($purchases->status_pembelian == "status3_ambil")
                        <p class="">Pesanan Telah Disiapkan. SILAHKAN AMBIL PESANAN ANDA</p>
                    @endif

                    @if($purchases->status_pembelian == "status2" || $purchases->status_pembelian == "status2_ambil")
                        <p class="">Pesanan Sedang Diproses. TUNGGU PESANAN DIPROSES.</p>
                    @endif

                    @if($purchases->status_pembelian == "status1" || $purchases->status_pembelian == "status1_ambil")
                        @if(!$cek_proof_of_payment)
                        <p class="">Belum Dapat Dikonfirmasi. KIRIM BUKTI PEMBAYARAN.</p>
                        
                        @elseif($cek_proof_of_payment)
                        <p class="">Bukti Pembayaran Telah Dikirim. MENUNGGU KONFIRMASI.</p>
                        @endif
                    @endif
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    </div><!-- End .row -->
    @endif

    @if($purchases->status_pembelian == "status3" || $purchases->status_pembelian == "status4_ambil_a")
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-dashboard">
                <div class="card-body">
                    @if($purchases->status_pembelian == "status3")
                    <p class="">Jika pesanan telah sampai di lokasi dan telah diterima. SILAHKAN KONFIRMASI PESANAN.</p>
                    @endif
                    
                    @if($purchases->status_pembelian == "status4_ambil_a")
                    <p class="">Jika pesanan telah diterima. SILAHKAN KONFIRMASI</p>
                    @endif
                    
                    <a href="../update_status_pembelian/{{$purchases->purchase_id}}" class="btn btn-primary btn-round">
                        <span>KONFIRMASI</span>
                    </a>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    </div><!-- End .row -->
    @endif

    @if($purchases->status_pembelian == "status1" || $purchases->status_pembelian == "status1_ambil")
        @if(!$cek_proof_of_payment)
        <form action="../PostBuktiPembayaran/{{$purchases->purchase_id}}" method="post" enctype="multipart/form-data" class="row" >
        @csrf
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <div class="fileUpload">
                            <input id="uploadBtn1" type="file" name="proof_of_payment_image" class="upload" accept="image/*" required/>
                            <input class="form-control" id="uploadFile1" placeholder="Pilih Foto..." disabled="disabled"/>
                            <small class="form-text">Pastikan gambar yang anda masukkan dapat dilihat dengan jelas.</small>
                        </div>
                        <script>
                            document.getElementById("uploadBtn1").onchange = function () {
                                document.getElementById("uploadFile1").value = this.value;
                            };
                        </script>
                        <button type="submit" class="btn btn-primary btn-round">
                            <span>KIRIM</span>
                        </button>
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </form><!-- End .row -->
        
        @elseif($cek_proof_of_payment)

        @endif
    @endif


    @if($purchases->status_pembelian == "status1" || $purchases->status_pembelian == "status2" 
    || $purchases->status_pembelian == "status3" || $purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status5" )
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
    
    @elseif($purchases->status_pembelian == "status1_ambil" || $purchases->status_pembelian == "status2_ambil")
    @if($cek_merchant_address > 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">Lokasi Toko </h3>
                    <table>
                        <tr>
                            <td>Provinsi</td>
                            <td>&emsp; : &emsp;</td>
                            <td> {{$lokasi_toko["province"]}} </td>
                        </tr>
                        <tr>
                            <td>Kota</td>
                            <td>&emsp; : &emsp;</td>
                            <td> {{$lokasi_toko["city"]}} </td>
                        </tr>
                        <tr>
                            <td>Kecamatan</td>
                            <td>&emsp; : &emsp;</td>
                            <td>{{$lokasi_toko["subdistrict_name"]}} </td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>&emsp; : &emsp;</td>
                            <td> {{$merchant_address->merchant_street_address}} </td>
                        </tr>
                    </table>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    </div><!-- End .row -->
    @elseif($cek_merchant_address == 0)

    @endif

    @else

    @endif

    <div class="row">
    @foreach($product_purchases as $product_purchases)
        <?php
            $jumlah_claim_voucher = DB::table('claim_vouchers')->where('checkout_id', $purchases->checkout_id)->count();
                
            $total_harga_pembelian = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
            ->where('purchases.checkout_id', $purchases->checkout_id)
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();
            
            $total_harga_pembelian_perproduk = $product_purchases->price * $product_purchases->jumlah_pembelian_produk;
            
            $jumlah_product_purchase = DB::table('product_purchases')->where('purchase_id', $purchases->purchase_id)->count();
        ?>
        @if($jumlah_claim_voucher == 0)
            <?php
                $total_harga_pembelian_produk = $total_harga_pembelian_perproduk;
                $total_harga_pembelian_produk_fix = "Rp." . number_format(floor($total_harga_pembelian_produk),2,',','.');
            ?>
        @else
            @foreach($claim_vouchers as $claim_voucher)
                @if($claim_voucher->checkout_id == $purchases->checkout_id)
                    <?php

                        $voucher = DB::table('vouchers')->where('voucher_id', $claim_voucher->voucher_id)->first();
                        
                        $potongan_harga_pembelian = $total_harga_pembelian->total_harga_pembelian * $voucher->potongan / 100;
                        $potongan_harga_barang = $total_harga_pembelian_perproduk * $voucher->potongan / 100;
                        if($potongan_harga_pembelian > $voucher->maksimal_pemotongan){
                            $potongan_harga_barang = $voucher->maksimal_pemotongan / $jumlah_product_purchase;
                            $potongan_harga_pembelian = $voucher->maksimal_pemotongan;
                        }
                        
                        $total_harga_pembelian_produk = (int)$total_harga_pembelian_perproduk - $potongan_harga_barang;
                        $total_harga_pembelian_produk_fix = "Rp." . number_format(floor($total_harga_pembelian_produk),2,',','.');
                        
                        $total_harga_pembelian_keseluruhan = (int)$total_harga_pembelian->total_harga_pembelian - $potongan_harga_pembelian;
                        // $total_harga_pembelian_keseluruhan_fix = "Rp." . number_format(floor($total_harga_pembelian_keseluruhan),2,',','.');
                    ?>
                @endif
            @endforeach
        @endif
        
        @if($jumlah_claim_voucher == 0)
            <script>
                const rupiah = (number)=>{
                    return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR"
                    }).format(number);
                }

                let total_harga_produk = document.getElementById("total_harga_produk");
                total_harga_produk.innerHTML = rupiah(<?php echo $total_harga_pembelian->total_harga_pembelian ?>);
            </script>
        @else
            <script>
                const rupiah = (number)=>{
                    return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR"
                    }).format(number);
                }

                let total_harga_produk = document.getElementById("total_harga_produk");
                total_harga_produk.innerHTML = rupiah(<?php echo $total_harga_pembelian_keseluruhan?>);
            </script>
        @endif
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <a href="../lihat_produk/{{$product_purchases->product_id}}"><h3 class="card-title">{{$product_purchases->product_name}}</h3></a>
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
                    <p>Jmlh: {{$product_purchases->jumlah_pembelian_produk}}</p>
                    <p>
                        <?php
                            // $harga_produk = "Rp " . number_format($potongan_checkout->jumlah_pembelian_produk,2,',','.');     
                            // echo $harga_produk
                        ?>
                        {{$total_harga_pembelian_produk_fix}}
                    </p>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    @endforeach
    </div><!-- End .row -->
</div><!-- .End .tab-pane -->

@endsection

