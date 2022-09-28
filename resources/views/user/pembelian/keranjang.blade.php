@extends('user/layout/main_dash')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <div class="page-content">
        <div class="cart">
            <div class="container">
                <div class="row">
                    <form action="./checkout" method="post" enctype="multipart/form-data" class="col-lg-12">
                        @csrf
                        <table class="table table-cart table-mobile">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($carts as $carts)
                                <tr>
                                    <td class="product-col">
                                        <div class="product">
                                            <input type="number" class="form-control" name="product_id[]" value="{{$carts->product_id}}" hidden required>
                                            <figure class="product-media">
                                                <a href="./lihat_produk/{{$carts->product_id}}">
                                                    <img src="./asset/u_file/product_image/{{$carts->product_image}}" alt="Product image">
                                                </a>
                                            </figure>

                                            <h3 class="product-title">
                                                <a href="./lihat_produk/{{$carts->product_id}}">{{$carts->product_name}}</a>
                                            </h3><!-- End .product-title -->
                                        </div><!-- End .product -->
                                    </td>
                                    <td class="price-col"  id="harga">
                                        <?php
                                            $harga_produk = "Rp." . number_format($carts->price,2,',','.');     
                                            echo $harga_produk
                                        ?>
                                    </td>
                                    <td class="quantity-col">
                                        <div class="cart-product-quantity">
                                            <input type="number" class="form-control" name="jumlah_masuk_keranjang[]" id="jumlah_masuk_keranjang[{{$carts->cart_id}}]" value="{{$carts->jumlah_masuk_keranjang}}" min="1" step="1" data-decimals="0" onchange="total{{$carts->cart_id}}()" required>
                                        </div><!-- End .cart-product-quantity -->
                                    </td>
                                    <td class="total-col" id="total_harga_table[{{$carts->cart_id}}]">
                                        <?php
                                            $total = $carts->price * $carts->jumlah_masuk_keranjang;
                                            $total_harga_produk = "Rp." . number_format($total,2,',','.');  
                                            echo $total_harga_produk;
                                        ?>
                                    </td>
                                    <script>
                                        function total{{$carts->cart_id}}()
                                        {
                                            let jumlah_barang<?php echo $carts->cart_id?> = document.getElementById("jumlah_masuk_keranjang[{{$carts->cart_id}}]").value;
                                            let total_harga_table<?php echo $carts->cart_id?> = document.getElementById("total_harga_table[{{$carts->cart_id}}]");
                                            
                                            const rupiah = (number)=>{
                                                return new Intl.NumberFormat("id-ID", {
                                                style: "currency",
                                                currency: "IDR"
                                                }).format(number);
                                            }
                                            total_harga_table<?php echo $carts->cart_id?>.innerHTML = rupiah(jumlah_barang<?php echo $carts->cart_id?> * <?php echo $carts->price?>) 
                                        }
                                    </script>
                                    <td class="remove-col"><a href="./hapus_keranjang/{{$carts->cart_id}}" class="btn-remove"><i class="icon-close"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table><!-- End .table table-wishlist -->
                        @if($cek_carts)
                        <div class="cart-bottom">
                            <button type="submit" class="btn btn-outline-primary-2"><span>CHECKOUT</span><i class="icon-long-arrow-right"></i></button>
                        </div><!-- End .cart-bottom -->
                        @endif
                    </form>

                    <!-- <aside class="col-lg-5">
                        <div class="summary summary-cart">
                            <h3 class="summary-title">Cart Total</h3>

                            <table class="table table-summary">
                                <tbody>
                                    <tr class="summary-subtotal">
                                        <td>Subtotal:</td>
                                        <td>$160.00</td>
                                    </tr>
                                    <tr class="summary-shipping">
                                        <td>Shipping:</td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr class="summary-shipping-row">
                                        <td>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="free-shipping" name="shipping" class="custom-control-input">
                                                <label class="custom-control-label" for="free-shipping">Free Shipping</label>
                                            </div>
                                        </td>
                                        <td>$0.00</td>
                                    </tr>

                                    <tr class="summary-shipping-row">
                                        <td>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="standart-shipping" name="shipping" class="custom-control-input">
                                                <label class="custom-control-label" for="standart-shipping">Standart:</label>
                                            </div>
                                        </td>
                                        <td>$10.00</td>
                                    </tr>

                                    <tr class="summary-shipping-row">
                                        <td>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="express-shipping" name="shipping" class="custom-control-input">
                                                <label class="custom-control-label" for="express-shipping">Express:</label>
                                            </div>
                                        </td>
                                        <td>$20.00</td>
                                    </tr>

                                    <tr class="summary-shipping-estimate">
                                        <td>Estimate for Your Country<br> <a href="dashboard.html">Change address</a></td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr class="summary-total">
                                        <td>Total:</td>
                                        <td>$160.00</td>
                                    </tr>
                                </tbody>
                            </table>

                            <a href="checkout.html" class="btn btn-outline-primary-2 btn-order btn-block">PROCEED TO CHECKOUT</a>
                        </div>

                        <a href="category.html" class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE SHOPPING</span><i class="icon-refresh"></i></a>
                    </aside> -->
                    
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .cart -->
    </div><!-- End .page-content -->
    
</div><!-- .End .tab-pane -->

@endsection

