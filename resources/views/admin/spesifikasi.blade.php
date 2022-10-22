@extends('admin/layout/main')

@section('title', 'Admin - Spesifikasi')

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
                <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-tambah_bank">Tambah Spesifikasi</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead align="center">
                    <tr>
                        <th>Nama Jenis Spesifikasi</th>
                        <th>Nama Spesifikasi</th>
                        <th colspan="1">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($specifications as $specifications)
                    <tr>
                        <td>{{$specifications->nama_jenis_spesifikasi}}</td>
                        <td>{{$specifications->nama_spesifikasi}}</td>
                        <td align="center" width="150px">
                            <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-edit-{{$specifications->specification_id}}">Edit</button>
                        </td>
                        <!-- <td align="center" width="100px">
                            <a href="./hapus_tipe_spesifikasi/{{$specifications->specification_id}}" class="btn btn-block btn-danger">Hapus</a>
                        </td> -->
                    </tr>

                    <div class="modal fade" id="modal-edit-{{$specifications->specification_id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Kategori</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card card-primary">
                                    <!-- form start -->
                                    <form action="./PostEditSpesifikasi/{{$specifications->specification_id}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="nama_jenis_spesifikasi">Nama Jenis Spesifikasi</label>
                                                <select class="form-control select2bs4" id="specification_type_id" name="specification_type_id" required>
                                                    <option disabled value="">{{$specifications->nama_jenis_spesifikasi}}</option>
                                                    <option selected value="{{$specifications->specification_type_id}}" hidden>{{$specifications->nama_jenis_spesifikasi}}</option>
                                                    @foreach($specification_types as $specification_types2)
                                                        <option value="{{$specification_types2->specification_type_id}}">{{$specification_types2->nama_jenis_spesifikasi}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nama_spesifikasi">Nama Spesifikasi</label>
                                                <input type="text" class="form-control" name="nama_spesifikasi" id="nama_spesifikasi" placeholder="Masukkan nama jenis spesifikasi." value="{{$specifications->nama_spesifikasi}}" required>
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
                <h4 class="modal-title">Tambah Jenis Spesifikasi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-primary">
                <!-- form start -->
                <form action="./PostTambahSpesifikasi" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_jenis_spesifikasi">Nama Jenis Spesifikasi</label>
                            <select class="form-control select2bs4" id="specification_type_id" name="specification_type_id" required>
                                <option selected disabled value="">Pilih Jenis Spesifikasi</option>
                                @foreach($specification_types as $specification_types)
                                <option value="{{$specification_types->specification_type_id}}">{{$specification_types->nama_jenis_spesifikasi}}</option>
                                @endforeach
                            </select>
                        </div>
                            
                        <div class="form-group">
                            <label for="nama_spesifikasi">Nama Spesifikasi</label>
                            <input type="text" class="form-control" name="nama_spesifikasi" id="nama_spesifikasi" placeholder="Masukkan nama jenis spesifikasi." required>
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