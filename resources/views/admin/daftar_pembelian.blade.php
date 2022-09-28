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
                  <thead>
                        <th align="center">ID Pesanan</th>
                        <th align="center">User ID</th>
                        <th align="center">Status Pesanan</th>
                        <th align="center" colspan="2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($purchases as $purchases)
                    <tr>
                        <td>{{$purchases->purchase_id}}</td>
                        <td>{{$purchases->user_id}}</td>
                        <td>
                          @if($purchases->status_pembelian == "status5")
                            PENJUALAN DAN PEMBELIAN BERHASIL.
                          @endif

                          @if($purchases->status_pembelian == "status4")
                            Transaksi Sukses. SILAHKAN KIRIM BAYARAN.
                          @endif

                          @if($purchases->status_pembelian == "status3")
                            Pesanan Sedang Dalam Perjalanan. TUNGGU PESANAN DITERIMA.
                          @endif

                          @if($purchases->status_pembelian == "status2")
                              Pesanan Sedang Diproses. TUNGGU PESANAN DIPROSES.
                          @endif

                          @if($purchases->status_pembelian == "status1")
                              @foreach($proof_of_payments as $proof_of_payment)
                                  @if($proof_of_payment->purchase_id == $purchases->purchase_id)
                                  Bukti Pembayaran Telah Dikirim. SILAHKAN KONFIRMASI.
                                  @else
                                      Belum Dapat Dikonfirmasi. TUNGGU BUKTI PEMBAYARAN.
                                  @endif
                              @endforeach
                          @endif
                        </td>
                        <td align="center" width="150px">
                          
                            @if($purchases->status_pembelian == "status2")
                            
                            @endif

                            @if($purchases->status_pembelian == "status1")
                                @foreach($proof_of_payments as $proof_of_payment)
                                    @if($proof_of_payment->purchase_id == $purchases->purchase_id)
                                    <a href="./update_status_pembayaran/{{$purchases->purchase_id}}" class="btn btn-block btn-info">Konfirmasi Pembayaran</a>
                                    @else

                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td align="center" width="100px">
                            <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-edit-{{$purchases->purchase_id}}">Cek</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="modal-edit-{{$purchases->purchase_id}}">
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
                                                @if($product_purchase->purchase_id == $purchases->purchase_id)
                                                    <a>Product ID: {{$product_purchase->product_id}}</a> |

                                                    <a>Nama Produk: {{$product_purchase->product_name}}</a> |
                                                    <a>Spesifikasi Produk: 
                                                        @foreach($product_specifications as $product_specification)
                                                            @if($product_specification->product_id == $product_purchase->product_id)
                                                                {{$product_specification->nama_spesifikasi}}, 
                                                            @endif
                                                        @endforeach
                                                    </a> |
                                                    <a>Jumlah Pembelian: {{$product_purchase->jumlah_pembelian_produk}}</a> |
                                                    <a>Harga: 
                                                    <?php
                                                        $harga_produk = "Rp " . number_format($product_purchase->price*$product_purchase->jumlah_pembelian_produk,2,',','.');     
                                                        echo $harga_produk
                                                    ?> 
                                                    </a> ||
                                                    <br>
                                                @endif
                                            @endforeach<br>
                                            @if($purchases->status_pembelian == "status1")
                                                @foreach($proof_of_payments as $proof_of_payment)
                                                    @if($proof_of_payment->purchase_id == $purchases->purchase_id)
                                                        <center><a href="./asset/u_file/proof_of_payment_image/{{$proof_of_payment->proof_of_payment_image}}" target="_blank">Lihat Foto Bukti Pembayaran</a></center>
                                                    @else
                                                        <center><a>Belum dapat dikonfirmasi. MENUNGGU PEMBAYARAN</a></center>
                                                    @endif
                                                @endforeach
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

<div class="modal fade" id="modal-tambah_bank">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Bank</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-primary">
                <!-- form start -->
                <form action="./PostTambahBank" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_bank">Nama Bank</label>
                            <input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="Masukkan nama bank." required>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection