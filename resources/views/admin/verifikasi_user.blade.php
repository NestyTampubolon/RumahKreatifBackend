@extends('admin/layout/main')

@section('title', 'Admin - Verifikasi User')

@section('container')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tabel Verifikasi User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active">Tabel Verifikasi User</li>
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
              <!-- <div class="card-header">
                <h3 class="card-title"></h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th align="center">ID User</th>
                        <th align="center">Username</th>
                        <th align="center">Email</th>
                        <th align="center">Status</th>
                        <th align="center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($verify_users as $verify_users)
                    <tr>
                        <td>{{$verify_users->user_id}}</td>
                        <td>{{$verify_users->username}}</td>
                        <td>{{$verify_users->email}}</td>
                        @if($verify_users->is_verified==1)
                            <td align="center"><small class="badge badge-success">Verified</small></td>
                        @else
                            <td align="center"><small class="badge badge-danger">No Verified</small></td>
                        @endif
                            <td align="center">
                              <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-cek-{{$verify_users->user_id}}">Cek</button>
                            </td>
                    </tr>
                    
                    <div class="modal fade" id="modal-cek-{{$verify_users->user_id}}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                  <h4 class="modal-title">Cek User</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                              <div class="modal-body">
                                  <center><a href="./asset/u_file/foto_ktp/{{$verify_users->foto_ktp}}" target="_blank">Lihat Foto KTP</a></center>
                                  <center><a href="./asset/u_file/foto_ktp_selfie/{{$verify_users->ktp_dan_selfie}}" target="_blank">Lihat Foto Selfie bersama KTP</a></center>
                                  <center><a>{{$verify_users->name}}</a></center>
                                  <center><a>{{$verify_users->no_hp}}</a></center>
                                  <center><a>{{$verify_users->birthday}}</a></center>
                                  <center><a>{{$verify_users->gender}}</a></center>
                              </div>
                              <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  @if($verify_users->is_verified==1)

                                  @else
                                  <a href="./verify_user/{{$verify_users->verify_id}}" class="btn btn-primary">Verify</a>
                                  @endif
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
@endsection