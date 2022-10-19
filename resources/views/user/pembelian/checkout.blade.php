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
                                    $harga_produk = "Rp." . number_format($cart->price,2,',','.');
                                    $subtotal = $cart->price * $cart->jumlah_masuk_keranjang;
                                    $subtotal_harga_produk = "Rp." . number_format($subtotal,2,',','.');  
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
                        <div class="summary summary-cart">
                            <h3 class="summary-title">Cart Total</h3>

                            <table class="table table-summary">
                                <tbody>
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>

                                    @foreach($carts as $carts)
                                    <tr>
                                        <td><a href="#">{{$carts->product_name}}</a></td>
                                        <td>
                                            <p id="subtotal_harga_produk">
                                                {{$subtotal_harga_produk}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <table class="table table-summary">
                                <tbody>
                                    <tr class="summary-shipping-estimate">
                                        <td>Gunakan Voucher:</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                                $jumlah_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
                                                ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->count();
                                            ?>
                                            @if($jumlah_vouchers > 0)
                                            <select name="voucher" id="voucher" class="custom-select form-control" required>
                                                <option value="" disabled selected>Pilih Voucher</option>
                                                @foreach($vouchers as $vouchers)
                                                @if($total_harga->total_harga >= $vouchers->minimal_pengambilan)
                                                    <option value="{{$vouchers->voucher_id}}">{{$vouchers->nama_voucher}} ({{$vouchers->potongan}}%)</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            @else
                                                <input class="form-control" value="Tidak ada voucher yang bisa diambil." disabled>
                                            @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-summary">
                                <tbody>
                                    <tr class="summary-shipping-estimate">
                                        <td>Metode Pembelian:</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" id="ambil_ditempat" name="metode_pembelian" value="ambil_ditempat" required>
                                            <label for="ambil_ditempat">Ambil Ditempat</label>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" id="pesanan_dikirim" name="metode_pembelian" value="pesanan_dikirim" disabled required>
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
                                            <select name="street_address" id="street_address" class="custom-select form-control">
                                                <!-- <option value="" disabled selected id="disabled_alamat">Pilih Alamat Pengiriman</option> -->
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
                                    <tr class="summary-shipping-estimate" id="servis_row">
                                        <td colspan="2">
                                            <label>Servis *</label>
                                            <select name="servis" id="servis" class="custom-select form-control">
                                                <option value="" disabled selected>Pilih Servis</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-summary">
                                <tbody>
                                    <tr class="summary-total" id="total_harga_checkout">
                                        <td>Total:</td>
                                        <td>
                                            <?php
                                                $total_harga_checkout = "Rp." . number_format($total_harga->total_harga,2,',','.');
                                            ?>
                                            {{$total_harga_checkout}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="checkout"></div>
                        </div>
                        <div id="ongkir">
                        </div>
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
    
    $("#alamat_table").hide();

    $("#province_address_row").hide();
    $("#city_address_row").hide();
    $("#subdistrict_address_row").hide();

    $("#pengiriman_table").hide();
    $("#servis_row").hide();
</script>
<script src="{{ URL::asset('asset/js/function.js') }}"></script>

@endsection

