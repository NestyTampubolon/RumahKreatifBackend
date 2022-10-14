@extends('admin/layout/main')

@section('title', 'Admin - Voucher')

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
              <div class="card-header">
                <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-tambah_voucher">Tambah Voucher</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead align="center">
                        <th>Nama Voucher</th>
                        <th>Potongan</th>
                        <th>Minimal Pengambilan</th>
                        <th>Maksimal Pemotongan</th>
                        <th>Tanggal Berlaku</th>
                        <th>Tanggal Batas Berlaku</th>
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($vouchers as $vouchers)
                    <?php
                        $minimal_pengambilan = "Rp " . number_format($vouchers->minimal_pengambilan,2,',','.');
                        $maksimal_pemotongan = "Rp " . number_format($vouchers->maksimal_pemotongan,2,',','.');
                    ?>
                    <tr>
                        <td>{{$vouchers->nama_voucher}}</td>
                        <td>{{$vouchers->potongan}}%</td>
                        <td>{{$minimal_pengambilan}}</td>
                        <td>{{$maksimal_pemotongan}}</td>
                        <td>{{$vouchers->tanggal_berlaku}}</td>
                        <td>{{$vouchers->tanggal_batas_berlaku}}</td>
                        <td align="center" width="100px">
                            <a href="./hapus_voucher/{{$vouchers->voucher_id}}" class="btn btn-block btn-danger">Hapus</a>
                        </td>
                    </tr>
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

<div class="modal fade" id="modal-tambah_voucher">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Voucher</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-primary">
                <!-- form start -->
                <form action="./PostTambahVoucher" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_voucher">Nama Voucher</label>
                            <input type="text" class="form-control" name="nama_voucher" id="nama_voucher" placeholder="Masukkan nama voucher." required>
                        </div>
                        
                        <div class="form-group">
                            <label for="potongan">Potongan</label>
                            <input type="number" class="form-control" name="potongan" id="potongan" placeholder="Masukkan potongan yang diberikan voucher. (%)" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="minimal_pengambilan">Minimal Pengambilan</label>
                            <input type="number" class="form-control" name="minimal_pengambilan" id="minimal_pengambilan" min="0" placeholder="Masukkan minimal belanja agar voucher dapat diambil." required>
                        </div>
                        
                        <div class="form-group">
                            <label for="maksimal_pemotongan">Maksimal Pemotongan</label>
                            <input type="number" class="form-control" name="maksimal_pemotongan" id="maksimal_pemotongan" min="0" placeholder="Masukkan maksimal potongan belanjaan yang didapat." required>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <label for="tanggal_berlaku">Tanggal Berlaku</label>
                                <input type="date" class="form-control" name="tanggal_berlaku" min="<?php echo date('Y-m-d'); ?>" id="tanggal_berlaku" required>
                            </div>
                            
                            <div class="col-6">
                                <label for="tanggal_batas_berlaku">Tanggal Batas Berlaku</label>
                                <input type="date" class="form-control" name="tanggal_batas_berlaku" min="<?php echo date('Y-m-d'); ?>" id="tanggal_batas_berlaku" required>
                            </div>
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