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
    @foreach($purchases as $purchases)
    
        @if($purchases->status_pembelian == "status1" || $purchases->status_pembelian == "status1_ambil")
            @if(!$cek_proof_of_payment)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-dashboard">
                        <div class="card-body">
                            <p class="">
                                SILAHKAN LAKUKAN PEMBAYARAN PESANAN ANDA SENILAI
                                <?php
                                    $ongkir = 30000;
                                    // $total_harga_produk = "Rp " . number_format($total_harga->total_harga + $ongkir,2,',','.');
                                    $total_harga_produk = "Rp " . number_format($total_harga->total_harga,2,',','.');
                                ?>
                                <b><a id="total_harga_produk">{{$total_harga_produk}}</a></b>

                                KE NOMOR REKENING DIBAWAH INI.<br>
                                <b>---------------</b>
                            </p>
                        </div><!-- End .card-body -->
                    </div><!-- End .card-dashboard -->
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-dashboard">
                        <div class="card-body">
                            <p class="">
                                <b>Keterangan Pembayaran: </b>
                            </p>
                            <p class="">
                                <?php
                                    $ongkir = 30000;
                                    $total_pembelian = "Rp " . number_format($total_harga->total_harga + $ongkir,2,',','.');
                                    $total_harga_produk = "Rp " . number_format($total_harga->total_harga,2,',','.');
                                    $ongkir = "Rp " . number_format($ongkir,2,',','.');
                                ?>
                                
                                <!-- (Total Harga Produk) <a id="set_harga_produk">{{$total_harga_produk}}</a> <a id="plus_ongkir">+ (Ongkos Kirim) {{$ongkir}}</a> = (Total Pembelian) <a id="total_pembelian">{{$total_pembelian}}</a> -->

                                (Total Harga Produk) <a id="set_harga_produk">{{$total_harga_produk}}</a> <a id="plus_ongkir">
                            </p>
                            <!-- <p class="">
                                <a href="#voucher_open" data-toggle="modal" title="My account">DAPATKAN VOUCHER</a>
                            </p> -->

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
                        || $purchases->status_pembelian == "status5" || $purchase->status_pembelian == "status5_ambil")
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <h6 class="">Alamat Toko : <br><br> {{$merchant_address->merchant_street_address}}</h6>
                        <p></p>
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </div><!-- End .row -->

        @else

        @endif

    @endforeach

    <div class="row">
    @foreach($product_purchases as $product_purchases)
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">{{$product_purchases->product_name}}</h3>
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
                            $harga_produk = "Rp " . number_format($product_purchases->price * $product_purchases->jumlah_pembelian_produk,2,',','.');     
                            echo $harga_produk
                        ?>
                    </p>
                </div><!-- End .card-body -->
            </div><!-- End .card-dashboard -->
        </div><!-- End .col-lg-6 -->
    @endforeach
    </div><!-- End .row -->
</div><!-- .End .tab-pane -->

<div class="modal fade" id="voucher_open" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="icon-close"></i></span>
                </button>

                <div class="form-box">
                    <div class="form-tab">
                        <div class="tab-content" id="tab-content-5">
                            <div class="tab-pane fade show active" id="voucher" role="tabpanel" aria-labelledby="voucher-tab" align="center">
                                <h3>53111411647</h3>
                                <button class="btn btn-primary btn-round" onclick="klaim_voucher()">
                                    <span>KLAIM</span>
                                </button>
                            </div><!-- .End .tab-pane -->
                            <script>
                                function klaim_voucher() {
                                    $("#voucher_open").modal("hide");
                                    let total_harga_produk = document.getElementById("total_harga_produk");
                                    let set_harga_produk = document.getElementById("set_harga_produk");
                                    let plus_ongkir = document.getElementById("plus_ongkir");
                                    let total_pembelian = document.getElementById("total_pembelian");

                                    const rupiah = (number)=>{
                                        return new Intl.NumberFormat("id-ID", {
                                        style: "currency",
                                        currency: "IDR"
                                        }).format(number);
                                    }
                                    total_harga_produk.innerHTML = rupiah(<?php echo $total_harga->total_harga ?>)
                                    set_harga_produk.innerHTML = rupiah(<?php echo $total_harga->total_harga ?>)
                                    plus_ongkir.innerHTML = ""
                                    total_pembelian.innerHTML = rupiah(<?php echo $total_harga->total_harga ?>)
                                }
                            </script>
                        </div><!-- End .tab-content -->
                    </div><!-- End .form-tab -->
                </div><!-- End .form-box -->
            </div><!-- End .modal-body -->
        </div><!-- End .modal-content -->
    </div><!-- End .modal-dialog -->
</div><!-- End .modal -->

@endsection

