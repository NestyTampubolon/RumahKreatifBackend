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
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        @if($purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status5")
                            <p class="">Pesanan Diterima. PEMBELIAN BERHASIL.</p>
                        @endif

                        @if($purchases->status_pembelian == "status3")
                            <p class="">Pesanan Sedang Dalam Perjalanan. TUNGGU PESANAN SAMPAI.</p>
                        @endif

                        @if($purchases->status_pembelian == "status2")
                            <p class="">Pesanan Sedang Diproses. TUNGGU PESANAN DIPROSES.</p>
                        @endif

                        @if($purchases->status_pembelian == "status1")
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
        
        
        @if($purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status5")

        @endif

        @if($purchases->status_pembelian == "status3")
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <p class="">Jika pesanan telah sampai di lokasi dan telah diterima. SILAHKAN KONFIRMASI PESANAN.</p>
                        <a href="../update_status_pembayaran/{{$purchases->purchase_id}}" class="btn btn-primary btn-round">
                            <span>KONFIRMASI</span>
                        </a>
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </div><!-- End .row -->
        @endif
        
        @if($purchases->status_pembelian == "status2")

        @endif

        @if($purchases->status_pembelian == "status1")
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

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-dashboard">
                    <div class="card-body">
                        <h6 class="">{{$purchases->alamat_purchase}}</h6>
                        <p></p>
                    </div><!-- End .card-body -->
                </div><!-- End .card-dashboard -->
            </div><!-- End .col-lg-6 -->
        </div><!-- End .row -->
    @endforeach

    <div class="row">
    @foreach($product_purchases as $product_purchases)
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body">
                    <h3 class="card-title">{{$product_purchases->product_name}}</h3>
                    <p> 
                    @foreach($product_specifications as $product_specification)
                        @if($product_specification->product_id == $product_purchases->product_id)
                            <a>{{$product_specification->nama_spesifikasi}},</a>&nbsp;
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
    @endforeach
    </div><!-- End .row -->
</div><!-- .End .tab-pane -->

@endsection

