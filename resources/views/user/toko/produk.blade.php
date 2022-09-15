@extends('user/toko/layout/main')

@section('title', 'Rumah Kreatif Toba - Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Toko</li>
@endsection

@section('container')

<div class="tab-pane fade show active" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
    <p>Tambahkan produk anda</p>
    <a class="nav-link btn btn-outline-primary-2" href="./tambah_produk/pilih_kategori">
        <span>TAMBAH PRODUK</span>
        <i class="icon-long-arrow-right"></i>
    </a>

    <div class="mb-4"></div>

    <div class="tab-content">
        <div class="tab-pane p-0 fade show active" id="top-all-tab" role="tabpanel" aria-labelledby="top-all-link">
            <div class="products">
                <div class="row justify-content-center">

                    @foreach($products as $products)
                    <div class="col-6 col-md-4 col-lg-3 col-xl-5col">
                        <div class="product product-11 text-center">
                            <figure class="product-media">
                                <a href="product.html">
                                    <img src="./asset/u_file/product_image/{{$products->product_image}}" alt="Product image" class="product-image">
                                    <!-- <img src="{{ URL::asset('asset/Molla/assets/images/demos/demo-2/products/product-7-2.jpg') }}" alt="Product image" class="product-image-hover"> -->
                                </a>

                                <div class="product-action-vertical">
                                    <a href="#" class="btn-product-icon btn-wishlist "><span>add to wishlist</span></a>
                                </div><!-- End .product-action-vertical -->
                            </figure><!-- End .product-media -->

                            <div class="product-body">
                                <div class="product-cat">
                                    <a href="#">{{$products->nama_kategori}}</a>
                                </div><!-- End .product-cat -->
                                <h3 class="product-title"><a href="product.html">{{$products->product_name}}</a></h3><!-- End .product-title -->
                                <div class="product-price">
                                    <?php
                                        $harga_produk = "Rp " . number_format($products->price,2,',','.');     
                                        echo $harga_produk
                                    ?>
                                </div><!-- End .product-price -->
                            </div><!-- End .product-body -->
                            <div class="product-action">
                                <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                            </div><!-- End .product-action -->
                        </div><!-- End .product -->
                    </div><!-- End .col-sm-6 col-md-4 col-lg-3 -->
                    @endforeach

                </div><!-- End .row -->
            </div><!-- End .products -->
        </div><!-- .End .tab-pane -->
    </div><!-- .End .tab-content -->
</div><!-- .End .tab-pane -->

@endsection

