@extends('admin/layout/main')

@section('title', 'Admin - Rekening')

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
                <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-tambah_bank">Tambah Jenis Spesifikasi</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th align="center">User ID</th>
                        <th align="center">Atas Nama</th>
                        <th align="center">Nama Bank</th>
                        <th align="center">Nomor Rekening</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($rekenings as $rekening)
                    @foreach($profiles as $profile)
                      @if($profile->user_id == $rekening->user_id)
                    <tr>
                        <td>{{$rekening->user_id}}</td>
                        <td>{{$rekening->atas_nama}}</td>
                        <td>{{$rekening->nama_bank}}</td>
                        <td>{{$rekening->nomor_rekening}}</td>
                    </tr>
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