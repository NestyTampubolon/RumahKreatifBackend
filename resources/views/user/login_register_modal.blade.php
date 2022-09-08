<style>
    .form-tab .nav.nav-pills .nav-link:hover{
        color: #800000;
    }

    .form-tab .form-footer .btn{
        color: #800000;
        border-color:  #800000;
    }
</style>

<!-- Sign in / Register Modal -->
<div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="icon-close"></i></span>
                </button>

                <div class="form-box">
                    <div class="form-tab">
                        <ul class="nav nav-pills nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="signin-tab" data-toggle="tab" href="#signin" role="tab" aria-controls="signin" aria-selected="true">Masuk</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Daftar</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="tab-content-5">
                            <div class="tab-pane fade show active" id="signin" role="tabpanel" aria-labelledby="signin-tab">
                            @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                Cek kembali akun anda.
                            </div><br>
                            @endif
                                <form action="./login" method="post">
                                @csrf
                                    <div class="form-group">
                                        <label for="username_email">Username atau E-mail *</label>
                                        <input type="text" class="form-control" id="username_email" name="username_email" required>
                                    </div><!-- End .form-group -->

                                    
                                    <div class="form-group">
                                        <label for="password">Password *</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-outline-primary-2">
                                            <span>MASUK</span>
                                            <i class="icon-long-arrow-right"></i>
                                        </button>

                                        <!-- <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="signin-remember">
                                            <label class="custom-control-label" for="signin-remember">Remember Me</label>
                                        </div> -->

                                        <!-- <a href="#" class="forgot-link">Forgot Your Password?</a> -->
                                    </div><!-- End .form-footer -->
                                </form>
                                <div class="form-choice" hidden>
                                    <p class="text-center">atau masuk melalui</p>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-login btn-g">
                                                <i class="icon-google"></i>
                                                Login With Google
                                            </a>
                                        </div><!-- End .col-6 -->
                                    </div><!-- End .row -->
                                </div><!-- End .form-choice -->
                            </div><!-- .End .tab-pane -->
                            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="tab">
                                <form action="./registrasi" method="post" enctype="multipart/form-data">
                                @csrf
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div><!-- End .form-group -->
                                    @error('username')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="form-group">
                                        <label for="email">E-Mail</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div><!-- End .form-group -->
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="gender">Jenis Kelamin</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option selected disabled value="">Pilih Jenis Kelamin</option>
                                            <option value="L">Laki Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="birthday">Tanggal Lahir</label>
                                        <input type="date" class="form-control" id="birthday" name="birthday" max="<?php echo date('Y-m-d')?>" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="no_hp">Nomor Handphone</label>
                                        <input type="text" class="form-control" id="no_hp" name="no_hp" maxlength="13" onkeypress="return hanyaAngka(event)" required>
                                    </div><!-- End .form-group -->

                                    <script>
                                        function hanyaAngka(event) {
                                            var angka = (event.which) ? event.which : event.keyCode
                                            if ((angka < 48 || angka > 57) )
                                                return false;
                                            return true;
                                        }
                                    </script>
                                    

                                    <div class="form-footer">
                                        <button id="daftar" type="submit" class="btn btn-outline-primary-2" disabled>
                                            <span>DAFTAR</span>
                                            <i class="icon-long-arrow-right"></i>
                                        </button>

                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="policy" onClick="toggle(this)">
                                            <label class="custom-control-label" for="policy">Saya setuju dengan <a href="#">kebijakan privasi</a> *</label>
                                        </div><!-- End .custom-checkbox -->
                                    </div><!-- End .form-footer -->
                                    <script>
                                        var button = document.getElementById('daftar')
                                        var checkbox = document.getElementById('policy')
                                        function toggle(source) {
                                            if(checkbox.checked = true){
                                                button.disabled = false;
                                            }
                                        }
                                    </script>
                                </form>

                                <div class="form-choice" hidden>
                                    <p class="text-center">atau masuk melalui</p>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <a href="#" class="btn btn-login btn-g">
                                                <i class="icon-google"></i>
                                                Login With Google
                                            </a>
                                        </div><!-- End .col-6 -->
                                    </div><!-- End .row -->
                                </div><!-- End .form-choice -->

                            </div><!-- .End .tab-pane -->
                        </div><!-- End .tab-content -->
                    </div><!-- End .form-tab -->
                </div><!-- End .form-box -->
            </div><!-- End .modal-body -->
        </div><!-- End .modal-content -->
    </div><!-- End .modal-dialog -->
</div><!-- End .modal -->