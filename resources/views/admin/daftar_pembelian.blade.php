@extends('admin/layout/main')

@section('title', 'Admin - Bank')

@section('container')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>DataTables</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">DataTables</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead align="center">
                        <th>ID Pesanan</th>
                        <th>User ID</th>
                        <th>Status Pesanan</th>
                        <th colspan="2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                    @foreach($checkouts as $checkout)
                      @foreach($purchases as $purchase)
                        @if($purchase->checkout_id == $checkout->checkout_id)
                        <tr>
                            <td>{{$purchase->purchase_id}}</td>
                            <td>{{$purchase->user_id}}</td>
                            <td>
                              @if($purchase->status_pembelian == "status5" || $purchase->status_pembelian == "status5_ambil")
                                PENJUALAN DAN PEMBELIAN BERHASIL.
                              @endif

                              @if($purchase->status_pembelian == "status4" || $purchase->status_pembelian == "status4_ambil_b")
                                Transaksi Sukses. SILAHKAN KIRIM BAYARAN.
                              @endif
                              
                              @if($purchase->status_pembelian == "status4_ambil_a")
                                Pesanan telah diberikan. TUNGGU PELANGGAN MENGKONFIRMASI PESANAN YANG TELAH DIAMBIL.
                              @endif

                              @if($purchase->status_pembelian == "status3")
                                Pesanan Sedang Dalam Perjalanan. TUNGGU PESANAN DITERIMA.
                              @endif
                              
                              @if($purchase->status_pembelian == "status3_ambil")
                                MENUNGGU PELANGGAN MENGAMBIL PESANAN.
                              @endif

                              @if($purchase->status_pembelian == "status2" || $purchase->status_pembelian == "status2_ambil")
                                  Pesanan Sedang Diproses. TUNGGU PESANAN DIPROSES.
                              @endif

                              <?php
                                  $proof_of_payments = DB::table('proof_of_payments')->where('purchase_id', $purchase->purchase_id)->first();
                              ?>
                              @if($purchase->status_pembelian == "status1" || $purchase->status_pembelian == "status1_ambil")
                                  @if($proof_of_payments)
                                      Bukti Pembayaran Telah Dikirim. SILAHKAN KONFIRMASI.
                                  @else
                                      Belum Dapat Dikonfirmasi. TUNGGU BUKTI PEMBAYARAN.
                                  @endif
                              @endif
                            </td>
                            <td align="center" width="150px">
                              
                                @if($purchase->status_pembelian == "status2" || $purchase->status_pembelian == "status2_ambil")
                                
                                @endif

                                @if($purchase->status_pembelian == "status1" || $purchase->status_pembelian == "status1_ambil")
                                    @if($proof_of_payments)
                                    <a href="./update_status_pembelian/{{$purchase->purchase_id}}" class="btn btn-block btn-info">Konfirmasi Pembayaran</a>
                                    @else

                                    @endif
                                @endif
                            </td>
                            <td align="center" width="100px">
                                <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-edit-{{$purchase->purchase_id}}">Cek</button>
                            </td>
                        </tr>
                        

                        <div class="modal fade" id="modal-edit-{{$purchase->purchase_id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Bank</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card card-primary">
                                        <!-- form start -->
                                        <form action="./PostEditBank/" method="post" enctype="multipart/form-data">
                                        @csrf
                                            <div class="card-body">
                                                <div class="form-group">
                                                @foreach($product_purchases as $product_purchase)
                                                    @if($product_purchase->purchase_id == $purchase->purchase_id)
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
                                                                        $total_harga_pembelian_old = "Rp." . number_format(floor($total_harga_pembelian),2,',','.');

                                                                        $potongan = $total_harga_pembelian * $voucher->potongan / 100 / $jumlah_product_purchase;
                                                                        $potongan_purchase = $total_harga_pembelian * $voucher->potongan / 100 ;
                                                                        $checkout_hasil_potong = $total_harga->total_harga * $voucher->potongan / 100;
                                                
                                                                        if($checkout_hasil_potong > $voucher->maksimal_pemotongan){
                                                                            $potongan = $voucher->maksimal_pemotongan / $jumlah_pembelian_checkout / $jumlah_product_purchase;
                                                                            $potongan_purchase = $voucher->maksimal_pemotongan / $jumlah_pembelian_checkout;
                                                                        }
                                                                
                                                                        $total_harga_fix = (int)$total_harga_pembelian - $potongan;
                                                                        $total_harga_checkout_fix = (int)$total_harga_semula->total_harga_semula - $potongan_purchase;

                                                                        $rp_total_harga_pembelian_fix = "Rp." . number_format(floor($total_harga_checkout_fix),2,',','.');
                                                                        $total_harga_semula = "Rp." . number_format(floor($total_harga_semula->total_harga_semula),2,',','.');
                                                                
                                                                        $total_harga_pembelian_fix = "Rp." . number_format(floor($total_harga_fix),2,',','.');
                                                                    ?>
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        <a>Product ID: {{$product_purchase->product_id}}</a> |

                                                        <a>Nama Produk: {{$product_purchase->product_name}}</a> |
                                                        <?php
                                                          $jumlah_product_specifications = DB::table('product_specifications')->where('product_id', $product_purchase->product_id)->count();
                                                        ?>
                                                        @if($jumlah_product_specifications == 0)

                                                        @else
                                                        <a>Spesifikasi Produk: 
                                                            @foreach($product_specifications as $product_specification)
                                                                @if($product_specification->product_id == $product_purchase->product_id)
                                                                    {{$product_specification->nama_spesifikasi}},
                                                                @endif
                                                            @endforeach
                                                        </a> |
                                                        @endif
                                                        <a>Jumlah Pembelian: {{$product_purchase->jumlah_pembelian_produk}}</a> |
                                                        <a>Harga: 
                                                            {{$total_harga_pembelian_fix}}
                                                        </a> ||
                                                        <br>
                                                    @endif
                                                @endforeach<br>
                                                @if($jumlah_claim_voucher == 0)
                                                    <center><a>TOTAL HARGA PEMBELIAN: {{$total_harga_semula}}</a></center><br>
                                                @else
                                                    <center><a>TOTAL HARGA PEMBELIAN: {{$rp_total_harga_pembelian_fix}}</a></center>
                                                    <center><a>TOTAL HARGA PEMBELIAN SEBELUM PEMOTONGAN: {{$total_harga_semula}}</a></center><br>
                                                @endif
                                                
                                                
                                                @if($purchase->status_pembelian == "status1" || $purchase->status_pembelian == "status1_ambil")
                                                    @if($proof_of_payments)
                                                        <center><a href="./asset/u_file/proof_of_payment_image/{{$proof_of_payments->proof_of_payment_image}}" target="_blank">Lihat Foto Bukti Pembayaran</a></center>
                                                    @else
                                                        <center><a>Belum dapat dikonfirmasi. MENUNGGU PEMBAYARAN</a></center>
                                                    @endif
                                                @endif
                                                
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal -->
                        @endif
                      @endforeach
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection