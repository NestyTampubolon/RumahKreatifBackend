@extends('admin/layout/main')

@section('title', 'Admin - Pembelian')

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
              <li class="breadcrumb-item"><a href="./">Home</a></li>
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
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Nama</th>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Status Pesanan</th>
                        <th>Update Status </th>
                        <th>Info</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($checkouts as $checkout)
                      @foreach($purchases as $purchase)
                        @if($purchase->checkout_id == $checkout->checkout_id)
                        <tr>
                            <td>{{$purchase->purchase_id}}</td>
                            @foreach($profiles as $profile)
                              @if($profile->user_id == $purchase->user_id)
                                <td>{{$profile->name}}</td>
                              @endif
                            @endforeach
                            <td>{{$purchase->user_id}}</td>
                            <td>{{$purchase->username}}</td>
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
                                <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-detail-{{$purchase->purchase_id}}">Cek</button>
                            </td>
                        </tr>
                        

                        <div class="modal fade" id="modal-detail-{{$purchase->purchase_id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Detail Pembelian</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <div class="form-group">
                                                @foreach($product_purchases as $product_purchase)
                                                    @if($product_purchase->purchase_id == $purchase->purchase_id)
                                                        <?php
                                                            $jumlah_claim_pembelian_voucher = DB::table('claim_vouchers')->where('tipe_voucher', 'pembelian')->where('checkout_id', $purchase->checkout_id)
                                                            ->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->count();
                                                            
                                                            $jumlah_claim_ongkos_kirim_voucher = DB::table('claim_vouchers')->where('tipe_voucher', 'ongkos_kirim')->where('checkout_id', $purchase->checkout_id)
                                                            ->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->count();
                                                                
                                                            $total_harga_pembelian = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
                                                            ->where('purchases.checkout_id', $purchase->checkout_id)
                                                            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                                            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                                                            ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();
                                                            
                                                            $total_harga_pembelian_perproduk = $product_purchase->price * $product_purchase->jumlah_pembelian_produk;
                                                            
                                                            $jumlah_product_purchase = DB::table('product_purchases')->where('purchase_id', $purchase->purchase_id)->count();
                                                            
                                                            $total_harga_pembelian_keseluruhan_tanpa_pemotongan = "Rp." . number_format(floor($total_harga_pembelian->total_harga_pembelian),2,',','.');
                                                            
                                                            $total_harga_pembelian_produk_tanpa_pemotongan = "Rp." . number_format(floor($total_harga_pembelian_perproduk),2,',','.');
                                                        ?>
                                                        
                                                        <a>Nama Toko: {{$product_purchase->nama_merchant}}</a> |

                                                        <a>Product ID: {{$product_purchase->product_id}}</a> |

                                                        <a>Nama Produk: {{$product_purchase->product_name}}</a> |
                                                        <?php
                                                          $jumlah_product_specifications = DB::table('product_specifications')->where('product_id', $product_purchase->product_id)->count();
                                                          $cek_target_kategori = 0;
                                                          $jumlah_potongan_subtotal_a = 0;
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

                                                        @if($jumlah_claim_pembelian_voucher == 0)
                                                            <?php
                                                                // $total_harga_pembelian_produk = $total_harga_pembelian_perproduk;
                                                                // $total_harga_pembelian_produk_fix = "Rp." . number_format(floor($total_harga_pembelian_produk),2,',','.');
                                                            ?>
                                                        @else
                                                            @foreach($claim_pembelian_vouchers as $claim_pembelian_voucher)
                                                                @if($claim_pembelian_voucher->checkout_id == $purchase->checkout_id)
                                                                    <?php                                                
                                                                        $target_kategori = explode(",", $claim_pembelian_voucher->target_kategori);

                                                                        foreach($target_kategori as $target_kategori){
                                                                            
                                                                            $subtotal_harga_produk = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
                                                                            ->where('purchases.checkout_id', $purchase->checkout_id)->where('category_id', $target_kategori)
                                                                            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                                                            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                                                                            ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();
                                                        
                                                                            $potongan_subtotal = [];
                                                                            $potongan_subtotal[] = (int)$subtotal_harga_produk->total_harga_pembelian * $claim_pembelian_voucher->potongan / 100;

                                                                            $potongan_subtotal_perproduk = (int)$total_harga_pembelian_perproduk * $claim_pembelian_voucher->potongan / 100;
                                                        
                                                                            $jumlah_potongan_subtotal = array_sum($potongan_subtotal);

                                                                            if($jumlah_potongan_subtotal <= $claim_pembelian_voucher->maksimal_pemotongan){
                                                                                if($product_purchase->category_id == $target_kategori){
                                                                                    $potongan_harga_barang = $potongan_subtotal_perproduk;
                                                                                }
                            
                                                                                else{
                                                                                    $potongan_harga_barang = 0;
                                                                                }
                                                                            }
                            
                                                                            else if($jumlah_potongan_subtotal > $claim_pembelian_voucher->maksimal_pemotongan){
                                                                                if($product_purchase->category_id == $target_kategori){
                                                                                    $potongan_harga_barang = $total_harga_pembelian_perproduk / $subtotal_harga_produk->total_harga_pembelian * $claim_pembelian_voucher->maksimal_pemotongan;
                                                                                }
                            
                                                                                else{
                                                                                    $potongan_harga_barang = 0;
                                                                                }
                                                                            }
                                                                            
                                                                            if($claim_pembelian_voucher->tipe_voucher == "pembelian"){
                                                                                $total_harga_pembelian_produk = (int)$total_harga_pembelian_perproduk - $potongan_harga_barang;
                                                                                $total_harga_pembelian_produk_fix = "Rp." . number_format(floor($total_harga_pembelian_produk),2,',','.');
                                                                            }
                                                                            
                                                                    ?>
                                                                    @if($target_kategori == $product_purchase->category_id)
                                                                        <?php
                                                                          if($jumlah_potongan_subtotal > $claim_pembelian_voucher->maksimal_pemotongan){
                                                                            $jumlah_potongan_subtotal = $claim_pembelian_voucher->maksimal_pemotongan;
                                                                          }
                                                                          $cek_target_kategori = $product_purchase->category_id; 
                                                                          $total_harga_pembelian_keseluruhan = (int)$total_harga_pembelian->total_harga_pembelian - $jumlah_potongan_subtotal;
                                                                          $total_harga_pembelian_keseluruhan_fix = "Rp." . number_format(floor($total_harga_pembelian_keseluruhan),2,',','.');
                                                                        ?>
                                                                        
                                                                        @if($jumlah_claim_pembelian_voucher == 0)
                                                                        <a>Harga: {{$total_harga_pembelian_produk_tanpa_pemotongan}}</a> ||
                                                                        @elseif($jumlah_claim_pembelian_voucher > 0)
                                                                        <a>Harga: {{$total_harga_pembelian_produk_fix}} dari {{$total_harga_pembelian_produk_tanpa_pemotongan}} </a> ||
                                                                        @endif
                                                                    
                                                                    @elseif($target_kategori != $product_purchase->category_id)

                                                                    @endif

                                                                    <?php
                                                                        }
                                                                    ?>
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        @if($product_purchase->category_id != $cek_target_kategori)
                                                            @if($jumlah_claim_pembelian_voucher == 0)
                                                            <a>Harga: {{$total_harga_pembelian_produk_tanpa_pemotongan}}</a> ||
                                                            @elseif($jumlah_claim_pembelian_voucher > 0)
                                                            <?php
                                                                $total_harga_pembelian_produk_no_potongan = $total_harga_pembelian_perproduk;
                                                                $total_harga_pembelian_produk_no_potongan_fix = "Rp." . number_format(floor($total_harga_pembelian_produk_no_potongan),2,',','.');
                                                            ?>
                                                            <a>Harga: {{$total_harga_pembelian_produk_no_potongan_fix}} dari {{$total_harga_pembelian_produk_tanpa_pemotongan}} </a> ||
                                                            @endif
                                                        @endif
                                                        <br>

                                                    @endif
                                                @endforeach<br>
                                                <?php
                                                  if($purchase->courier_code = "pos"){ $courier_name = "POS Indonesia (POS)"; }
                                                  elseif($purchase->courier_code = "jne"){ $courier_name = "Jalur Nugraha Eka (JNE)"; }
                                                ?>
                                                @if($jumlah_claim_pembelian_voucher == 0)
                                                    <center><a>TOTAL HARGA PEMBELIAN: {{$total_harga_pembelian_keseluruhan_tanpa_pemotongan}}</a></center><br>
                                                    @if($purchase->courier_code != "" && $purchase->service != "")
                                                      <?php
                                                        $ongkir = $purchase->ongkir; 
                                                        $ongkir_fix = "Rp." . number_format(floor($ongkir),2,',','.');
                                                      ?>
                                                        @if($jumlah_claim_ongkos_kirim_voucher != 0)
                                                          @foreach($claim_ongkos_kirim_vouchers as $claim_ongkos_kirim_voucher)
                                                            @if($claim_ongkos_kirim_voucher->checkout_id == $purchase->checkout_id)
                                                              <?php
                                                                $ongkir_get_voucher = $purchase->ongkir - (int)$claim_ongkos_kirim_voucher->potongan;
                                                                if($ongkir_get_voucher < 0 ){
                                                                  $ongkir_get_voucher = 0;
                                                                }
                                                                $ongkir_get_voucher_fix = "Rp." . number_format(floor($ongkir_get_voucher),2,',','.');
                                                                
                                                                $total_bayar = (int)$total_harga_pembelian->total_harga_pembelian + $ongkir_get_voucher;
                                                              ?>
                                                            <center><a>KURIR yang digunakan: {{$courier_name}} - {{$purchase->service}} dengan biaya ONGKOS KIRIM {{$ongkir_get_voucher_fix}} dari {{$ongkir_fix}}</a></center><br>
                                                            @endif

                                                          @endforeach
                                                        @else
                                                          <?php $total_bayar = (int)$total_harga_pembelian->total_harga_pembelian + $ongkir; ?>
                                                          <center><a>KURIR yang digunakan: {{$courier_name}} - {{$purchase->service}} dengan biaya ONGKOS KIRIM {{$ongkir_fix}}</a></center><br>
                                                        @endif
                                                      <?php
                                                        $total_bayar_ke_penjual = (int)$total_harga_pembelian->total_harga_pembelian + $ongkir;
                                                        $total_bayar_ke_penjual_fix = "Rp." . number_format(floor($total_bayar_ke_penjual),2,',','.');
                                                        $total_bayar_fix = "Rp." . number_format(floor($total_bayar),2,',','.');
                                                      ?>
                                                      <center><a>TOTAL PEMBAYARAN PEMBELI: {{$total_bayar_fix}}</a></center>
                                                      <center><a>TOTAL PEMBAYARAN KE PENJUAL: {{$total_bayar_ke_penjual_fix}}</a></center><br>
                                                    @endif
                                                @elseif($jumlah_claim_pembelian_voucher > 0)
                                                    <center><a>TOTAL HARGA PEMBELIAN: {{$total_harga_pembelian_keseluruhan_fix}}</a></center>
                                                    <center><a>TOTAL HARGA PEMBELIAN SEBELUM PEMOTONGAN: {{$total_harga_pembelian_keseluruhan_tanpa_pemotongan}}</a></center><br>

                                                    @if($purchase->courier_code != "" && $purchase->service != "")
                                                      <?php
                                                        $ongkir = $purchase->ongkir; 
                                                        $ongkir_fix = "Rp." . number_format(floor($ongkir),2,',','.');
                                                      ?>
                                                        @if($jumlah_claim_ongkos_kirim_voucher != 0)
                                                          @foreach($claim_ongkos_kirim_vouchers as $claim_ongkos_kirim_voucher)
                                                            @if($claim_ongkos_kirim_voucher->checkout_id == $purchase->checkout_id)
                                                              <?php
                                                                $ongkir_get_voucher = $purchase->ongkir - (int)$claim_ongkos_kirim_voucher->potongan;
                                                                if($ongkir_get_voucher < 0 ){
                                                                  $ongkir_get_voucher = 0;
                                                                }
                                                                $ongkir_get_voucher_fix = "Rp." . number_format(floor($ongkir_get_voucher),2,',','.');
                                                                $total_bayar_get_voucher = $total_harga_pembelian_keseluruhan + $ongkir_get_voucher;
                                                              ?>
                                                            <center><a>KURIR yang digunakan: {{$courier_name}} - {{$purchase->service}} dengan biaya ONGKOS KIRIM {{$ongkir_get_voucher_fix}} dari {{$ongkir_fix}}</a></center><br>
                                                            @endif

                                                          @endforeach
                                                        @else
                                                          <?php $total_bayar_get_voucher = (int)$total_harga_pembelian_keseluruhan + $ongkir; ?>
                                                          <center><a>KURIR yang digunakan: {{$courier_name}} - {{$purchase->service}} dengan biaya ONGKOS KIRIM {{$ongkir_fix}}</a></center><br>
                                                        @endif
                                                      <?php
                                                        $total_bayar_ke_penjual = (int)$total_harga_pembelian->total_harga_pembelian + $ongkir;
                                                        $total_bayar_ke_penjual_fix = "Rp." . number_format(floor($total_bayar_ke_penjual),2,',','.');
                                                        $total_bayar_get_voucher_fix = "Rp." . number_format(floor($total_bayar_get_voucher),2,',','.');
                                                      ?>
                                                      <center><a>TOTAL PEMBAYARAN PEMBELI: {{$total_bayar_get_voucher_fix}}</a></center>
                                                      <center><a>TOTAL PEMBAYARAN KE PENJUAL: {{$total_bayar_ke_penjual_fix}}</a></center><br>
                                                    @endif
                                                @endif
                                                
                                                @if($proof_of_payments)
                                                    <center><a href="./asset/u_file/proof_of_payment_image/{{$proof_of_payments->proof_of_payment_image}}" target="_blank">Lihat Foto Bukti Pembayaran</a></center>
                                                @endif

                                                @if($purchase->status_pembelian == "status1" || $purchase->status_pembelian == "status1_ambil")
                                                    @if(!$proof_of_payments)
                                                        <center><a>Belum dapat dikonfirmasi. MENUNGGU PEMBAYARAN</a></center>
                                                    @endif
                                                @endif
                                                
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
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