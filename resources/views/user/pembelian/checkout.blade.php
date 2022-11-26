@extends('user/pembelian/layout/main_checkout')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}" />

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <div class="page-content">
        <div class="cart">
            <div class="container">
                <form action="../PostBeliProduk" method="post" enctype="multipart/form-data" class="row">
                    <input type="text" name="merchant_id" value="{{$merchant_id}}" readonly hidden>
                    <div class="col-lg-9">
                        @csrf
                        <table class="table table-cart table-mobile">
                            <thead>
                                <tr align="center">
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($carts as $cart)
                                <?php
                                    $harga_produk = "Rp." . number_format($cart->price,0,',','.');
                                    $subtotal = $cart->price * $cart->jumlah_masuk_keranjang;
                                    $subtotal_harga_produk = "Rp." . number_format($subtotal,0,',','.');  
                                ?>
                                <tr align="center">
                                    <td class="product-col">
                                        <div class="product">
                                            <input type="number" class="form-control" name="product_id[]" value="{{$cart->product_id}}" hidden required>
                                            <figure class="product-media">
                                                <a href="../lihat_produk/{{$cart->product_id}}">
                                                    <?php
                                                        $product_images = DB::table('product_images')->select('product_image_name')->where('product_id', $cart->product_id)->orderBy('product_image_id', 'asc')->limit(1)->get();
                                                    ?>
                                                    @foreach($product_images as $product_image)
                                                        <img src="../asset/u_file/product_image/{{$product_image->product_image_name}}" alt="{{$cart->product_name}}">
                                                    @endforeach
                                                </a>
                                            </figure>

                                            <h3 class="product-title">
                                                <a href="../lihat_produk/{{$cart->product_id}}">{{$cart->product_name}}</a>
                                            </h3><!-- End .product-title -->
                                        </div><!-- End .product -->
                                    </td>
                                    <td>
                                        {{$harga_produk}}
                                    </td>
                                    <td class="quantity-col">
                                        <div class="cart-product-quantity">
                                            {{$cart->jumlah_masuk_keranjang}}
                                        </div><!-- End .cart-product-quantity -->
                                    </td>
                                    <td>
                                        {{$subtotal_harga_produk}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table><!-- End .table table-wishlist -->
                        <div class="cart-bottom">
                            <div class="input-group-append">
                                <a href="../keranjang" class="btn btn-outline-primary-2" type="submit">KEMBALI</a>
                            </div><!-- .End .input-group-append -->
                        </div><!-- End .cart-bottom -->
                    </div>

                    @if($cek_cart > 0)
                    <aside class="col-lg-3">
                        <div class="summary">
                            <h3 class="summary-title">Cart Total</h3>

                            <table class="table table-summary">
                                <tbody>
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>

                                    @foreach($carts as $carts1)
                                    <?php
                                        $subtotal = $carts1->price * $carts1->jumlah_masuk_keranjang;
                                        $subtotal_harga_produk = "Rp." . number_format($subtotal,0,',','.');  
                                    ?>
                                    <tr>
                                        <td><a href="../lihat_produk/{{$carts1->product_id}}">{{$carts1->product_name}}</a></td>
                                        <td>
                                            <p id="subtotal_harga_produk_{{$carts1->product_id}}">
                                                <a>{{$subtotal_harga_produk}}</a>
                                            </p>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <table class="table table-summary">
                                <tbody>
                                    <tr class="summary-shipping-estimate">
                                        <td colspan="2">Gunakan Voucher Pembelian:</td>
                                    </tr>
                                    <?php
                                        $jumlah_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
                                        ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->count();
                                        
                                        $jumlah_pembelian_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
                                        ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('tipe_voucher', "pembelian")->count();
                                        
                                        // $jumlah_ongkos_kirim_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
                                        // ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('tipe_voucher', "ongkos_kirim")->count();
                                    ?>
                                    @if($jumlah_vouchers > 0)
                                        <?php
                                            $cek_pembelian_vouchers = 0;
                                            foreach($get_pembelian_vouchers as $cek_get_pembelian_voucher){
                                                $cek_target_kategori = explode(",", $cek_get_pembelian_voucher->target_kategori);
                                                foreach($cek_target_kategori as $cek_target_kategori_get){
                                                    foreach($carts as $cek_cart_voucher){
                                                        if($cek_target_kategori_get == $cek_cart_voucher->category_id){
                                                            $cek_pembelian_vouchers = 1;
                                                        }
                                                        // $cek_pembelian_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
                                                        // ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('minimal_pengambilan', '<', $total_harga->total_harga)
                                                        // ->where('target_kategori', $cek_cart_voucher->category_id)->where('tipe_voucher', "pembelian")
                                                        // ->orderBy('nama_voucher', 'asc')->first();
                                                    }
                                                }

                                            }
                                        ?>
                                        @if($jumlah_pembelian_vouchers > 0 && $cek_pembelian_vouchers != 0)
                                        <tr id="voucher_pembelian_tr">
                                            <td colspan="2" id="voucher_pembelian_td">
                                                <select name="voucher_pembelian" id="voucher_pembelian" class="custom-select form-control" required>
                                                    <option value="" disabled selected>Pilih Voucher Pembelian</option>
                                                    @foreach($get_pembelian_vouchers as $pembelian_voucher)
                                                        <?php
                                                            $get_target_kategori = explode(",", $pembelian_voucher->target_kategori);
                                                            $cek_get_target_kategori_voucher = 0;
                                                        ?>
                                                        @foreach($carts as $carts2)
                                                            @foreach($get_target_kategori as $target_kategori)
                                                                @if($target_kategori == $carts2->category_id && $cek_get_target_kategori_voucher != $pembelian_voucher->voucher_id)
                                                                <option value="{{$pembelian_voucher->voucher_id}}">{{$pembelian_voucher->nama_voucher}} ({{$pembelian_voucher->potongan}}%)</option>
                                                                <?php $cek_get_target_kategori_voucher = $pembelian_voucher->voucher_id; ?>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="2">
                                                <input class="form-control" value="Tidak ada voucher pembelian." disabled>
                                            </td>
                                        </tr>
                                        @endif
                                    @else
                                    <tr>
                                        <td colspan="2">
                                            <input class="form-control" value="Tidak ada voucher yang bisa diambil." disabled>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            <table class="table table-summary">
                                <tbody>
                                    <tr class="summary-shipping-estimate">
                                        <td>Metode Pembelian:</td>
                                        <td></td>
                                    </tr>
                                    <tr id="tr_ambil_ditempat">
                                        <td id="td_ambil_ditempat">
                                            <input type="radio" id="ambil_ditempat" name="metode_pembelian" value="ambil_ditempat" required>
                                            <label for="ambil_ditempat">Ambil Ditempat</label>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr id="tr_pesanan_dikirim">
                                        <td id="td_pesanan_dikirim">
                                            <input type="radio" id="pesanan_dikirim" name="metode_pembelian" value="pesanan_dikirim" required>
                                            <label for="pesanan_dikirim">Pesanan Dikirim</label>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-summary" id="alamat_table">
                                <tbody>
                                    <tr class="summary-shipping-estimate">
                                        <td>Alamat Anda:</td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr class="">
                                        <td colspan="2">
                                            <label>Jalan *</label>
                                            <select name="alamat_purchase" id="street_address" class="custom-select form-control">
                                                <option value="" id="disabled_alamat" disabled selected>Pilih Alamat Pengiriman</option>
                                                @foreach($user_address as $user_address)
                                                    <option value="{{$user_address->user_address_id}}">{{$user_address->user_street_address}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr class="" id="province_address_row">
                                        <td colspan="2">
                                            <label>Provinsi *</label>
                                            <select name="province" id="province_address" class="custom-select form-control">
                                                <option value="" disabled selected>Pilih Provinsi</option>
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr class="" id="city_address_row">
                                        <td colspan="2">
                                            <label>Kabupaten/Kota *</label>
                                            <select name="city" id="city_address" class="custom-select form-control">
                                                <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                                            </select>
                                        </td>
                                    </tr>
                                    
                                    <tr class="" id="subdistrict_address_row">
                                        <td colspan="2">
                                            <label>Kecamatan *</label>
                                            <select name="subdistrict" id="subdistrict_address" class="custom-select form-control">
                                                <option value="" disabled selected>Pilih Kecamatan</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-summary" id="pengiriman_table">
                                <tbody>
                                    <tr class="summary-shipping-estimate">
                                        <td>Pengiriman</td>
                                        <td></td>
                                    </tr>
                                    <tr class="summary-shipping-estimate">
                                        <td colspan="2">
                                            <label>Kurir *</label>
                                            <select name="courier" id="courier" class="custom-select form-control">
                                                <option value="" disabled selected>Pilih Kurir</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr class="summary-shipping-estimate" id="service_row">
                                        <td colspan="2">
                                            <label>Servis *</label>
                                            <select name="service" id="service" class="custom-select form-control">
                                                <option value="" id="disabled_service" disabled selected>Pilih Servis</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <table class="table table-summary" id="voucher_ongkir_table">
                                <tbody>
                                    <tr class="summary-shipping-estimate">
                                        <td colspan="2">Gunakan Voucher Ongkos Kirim:</td>
                                    </tr>
                                    @if($jumlah_vouchers > 0)
                                        @if($cek_ongkos_kirim_vouchers > 0)
                                        <tr id="voucher_ongkos_kirim_tr">
                                            <td colspan="2" id="voucher_ongkos_kirim_td">
                                                <select name="voucher_ongkos_kirim" id="voucher_ongkos_kirim" class="custom-select form-control">
                                                    <option value="" id="disabled_voucher_ongkir" disabled selected>Pilih Voucher Ongkos Kirim</option>
                                                    @foreach($get_ongkos_kirim_vouchers as $ongkos_kirim_voucher)
                                                        <?php
                                                            $rp_potongan_ongkir = "Rp " . number_format($ongkos_kirim_voucher->potongan,0,',','.');
                                                        ?>
                                                        <option value="{{$ongkos_kirim_voucher->voucher_id}}">{{$ongkos_kirim_voucher->nama_voucher}} ({{$rp_potongan_ongkir}})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td colspan="2">
                                                <input class="form-control" value="Tidak ada voucher ongkos kirim." disabled>
                                            </td>
                                        </tr>
                                        @endif
                                    @else
                                    <tr>
                                        <td colspan="2">
                                            <input class="form-control" value="Tidak ada voucher yang bisa diambil." disabled>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>

                            <input name="harga_pembelian" value="{{$total_harga->total_harga}}" hidden>
                            
                            <table class="table table-summary">
                                <?php
                                    $rp_total_harga_checkout = "Rp." . number_format($total_harga->total_harga,0,',','.');
                                ?>
                                <tbody>
                                    <tr class="summary-subtotal" id="invoice_subtotal">
                                        <td>Subtotal:</td>
                                        <td>
                                            {{$rp_total_harga_checkout}}
                                        </td>
                                    </tr>

                                    <tr class="summary-subtotal" id="invoice_ongkir">
                                    </tr>
                                    
                                    <tr class="summary-subtotal" id="jumlah_potongan_subtotal">
                                        <input name="potongan_pembelian" value="0" hidden>
                                    </tr>

                                    <tr class="summary-subtotal" id="total_potongan_ongkir">
                                    </tr>

                                    <tr class="summary-total">
                                        <td>Total:</td>
                                        <td id="total_harga_checkout">
                                            <input name="potongan_pembelian" value="0" hidden>
                                            {{$rp_total_harga_checkout}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="checkout"></div>
                        </div>

                        <!-- <div id="ongkir">
                        </div> -->

                        
                    </aside>
                @elseif($cek_cart == 0)
                
                @endif
                </form><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .cart -->
    </div><!-- End .page-content -->
</div><!-- .End .tab-pane -->

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $merchant_id = <?php echo $merchant_id ?>;
    
    $total_berat = <?php echo $total_berat->total_berat ?>;
    $province_id = <?php echo $merchant_address->province_id ?>;
    $city_id = <?php echo $merchant_address->city_id ?>;
    $subdistrict_id = <?php echo $merchant_address->subdistrict_id ?>;
    $total_harga_checkout = <?php echo $total_harga->total_harga ?>;
    $total_harga_checkout_mentah = 0;
    $ongkir = 0;
    $potongan_pembelian = 0;
    $potongan_ongkir = 0;
    
    $("#voucher_ongkir_table").hide();

    $("#alamat_table").hide();

    $("#province_address_row").hide();
    $("#city_address_row").hide();
    $("#subdistrict_address_row").hide();

    $("#pengiriman_table").hide();
    $("#service_row").hide();
    
</script>
<script src="{{ URL::asset('asset/js/function.js') }}"></script>

@endsection

