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
                <form action="./PostBeliProduk" method="post" enctype="multipart/form-data" class="row">
                    <div class="col-lg-9">
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
                                                    @foreach($product_images as $product_image)
                                                        @if($product_image->product_id == $cart->product_id)
                                                            @if($loop->iteration % 3 == 0)
                                                            <img src="./asset/u_file/product_image/{{$product_image->product_image_name}}" alt="Product image">
                                                            @elseif($loop->iteration % 6 == 0)
                                                            
                                                            @else
                                                            
                                                            @endif
                                                        @endif
                                                    @endforeach
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
                    </div>

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
                                        <!-- <input type="">{{$carts->product_id}}</input>
                                        <input type="">{{$carts->jumlah_masuk_keranjang}}</input> -->
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
                                        <td colspan="2">
                                            Alamat Anda:<br> 
                                            <input type="text" name="alamat_purchase" class="form-control" style="background-color:white" required>
                                        </td>
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
                            <button type="submit" class="btn btn-outline-primary-2 btn-order btn-block">LANJUTKAN PEMBELIAN</button>
                        </div>
                    </aside>
                </form><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .cart -->
    </div><!-- End .page-content -->
    
</div><!-- .End .tab-pane -->

@endsection

