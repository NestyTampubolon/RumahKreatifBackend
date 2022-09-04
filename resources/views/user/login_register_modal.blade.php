<style>
    .menu li:hover>a, .menu li.show>a, .menu li.active>a{
        color: #800000;
    }

    .header-bottom .menu>li>a:before{
        background-color: #800000;
    }

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
                                <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">Daftar</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="tab-content-5">
                            <div class="tab-pane fade show active" id="signin" role="tabpanel" aria-labelledby="signin-tab">
                                <form action="./login" method="post">
                                @csrf
                                    <div class="form-group">
                                        <label for="singin-email">Username atau E-mail *</label>
                                        <input type="text" name="username_email" class="form-control" id="singin-email" name="singin-email" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="singin-password">Password *</label>
                                        <input type="password" name="password" class="form-control" id="singin-password" name="singin-password" required>
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
                            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                <form action="./registrasi" method="post" enctype="multipart/form-data">
                                @csrf
                                    <div class="form-group">
                                        <label for="register-nama">Nama</label>
                                        <input type="text" name="name" class="form-control" id="register-nama" name="register-nama" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="register-username">Username</label>
                                        <input type="text" name="username" class="form-control" id="register-username" name="register-username" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="register-email">E-Mail</label>
                                        <input type="email" name="email" class="form-control" id="register-password" name="register-password" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="register-password">Password</label>
                                        <input type="password" name="password" class="form-control" id="register-password" name="register-password" required>
                                    </div><!-- End .form-group -->

                                    <div class="form-group">
                                        <label for="register-no_WA">Nomor Whatsapp</label>
                                        <input type="text" name="no_WA" class="form-control" id="register-no_WA" name="register-no_WA" maxlength="13" onkeypress="return hanyaAngka(event)" required>
                                    </div><!-- End .form-group -->
                                    <div class="form-group">
                                        <label for="register-no_HP">Nomor Handphone</label>
                                        <input type="text" name="no_HP" class="form-control" id="register-no_HP" name="register-no_HP" maxlength="13" onkeypress="return hanyaAngka(event)" required>
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
                                            <input type="checkbox" class="custom-control-input" id="register-policy" onClick="toggle(this)">
                                            <label class="custom-control-label" for="register-policy">Saya setuju dengan <a href="#">kebijakan privasi</a> *</label>
                                        </div><!-- End .custom-checkbox -->
                                    </div><!-- End .form-footer -->
                                    <script>
                                        var button = document.getElementById('daftar')
                                        var checkbox = document.getElementById('register-policy')
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