@extends('user/pembelian/layout/main_checkout')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <div class="page-content">
        <div class="cart">
            <div class="container">
                <div class="row">
                    <form action="./PostBeliProduk" method="post" enctype="multipart/form-data" class="col-lg-9">
                        @csrf
                        <table class="table table-cart table-mobile">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th align="center">Jumlah</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($carts as $cart)
                                <tr>
                                    <td class="product-col">
                                        <div class="product">
                                            <input type="number" class="form-control" name="product_id[]" value="{{$cart->product_id}}" hidden required>
                                            <figure class="product-media">
                                                <a href="./lihat_produk/{{$cart->product_id}}">
                                                    <img src="./asset/u_file/product_image/{{$cart->product_image}}" alt="Product image">
                                                </a>
                                            </figure>

                                            <h3 class="product-title">
                                                <a href="./lihat_produk/{{$cart->product_id}}">{{$cart->product_name}}</a>
                                            </h3><!-- End .product-title -->
                                        </div><!-- End .product -->
                                    </td>
                                    <td class="quantity-col">
                                        <div class="cart-product-quantity">
                                            {{$cart->jumlah_masuk_keranjang}}
                                        </div><!-- End .cart-product-quantity -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table><!-- End .table table-wishlist -->
                        <div class="cart-bottom">
                            <div class="input-group-append">
                                <a href="./keranjang" class="btn btn-outline-primary-2" type="submit">KEMBALI</a>
                            </div><!-- .End .input-group-append -->
                        </div><!-- End .cart-bottom -->
                    </form>

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
                                            <?php
                                                $subtotal = $carts->price * $carts->jumlah_masuk_keranjang;
                                                $subtotal_harga_produk = "Rp." . number_format($subtotal,2,',','.');  
                                                echo $subtotal_harga_produk;
                                            ?>
                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr class="summary-shipping-estimate">
                                        <td>Alamat Anda<br> <a href="dashboard.html">Ganti alamat</a></td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr class="summary-total">
                                        <td>Total:</td>
                                        <td>
                                            <?php
                                                $total_harga_checkout = "Rp." . number_format($total_harga->total_harga,2,',','.');  
                                                echo $total_harga_checkout;
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <a href="#" class="btn btn-outline-primary-2 btn-order btn-block">LANJUTKAN PEMBELIAN</a>
                        </div>
                    </aside>
                    
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .cart -->
    </div><!-- End .page-content -->
    
</div><!-- .End .tab-pane -->

@endsection

