@extends('user/layout/main')

@section('title', 'Rumah Kreatif Toba')

<style>
    .intro-slider-container, .intro-slide{
        height:300px;
    }
</style>

@section('container')
<main class="main">
    <div class="bg-light ">
        <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
            <div class="container d-flex align-items-center">
                @if($kategori_produk_id > 0)
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/produk') }}">Produk</a></li>
                        <li class="breadcrumb-item">Kategori</li>
                        <li class="breadcrumb-item active">{{$nama_kategori->nama_kategori}}</li>
                    </ol>
                @else
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('/produk') }}">Produk</a></li>
                    </ol>
                @endif
            </div>
        </nav>
    </div>

    <div class="page-content">
        <div class="container">
            <?php
                // $product_id = $product_info->product_id;
                // $category_id = $product_info->category_id;
            ?>
            <div class="row">
                <aside class="col-lg-3 order-lg-first">
                    <div class="sidebar sidebar-shop">

                        <div class="mb-3"></div>

                        <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true" aria-controls="widget-1">
                                    Kategori
                                </a>
                            </h3><!-- End .widget-title -->

                            <div class="collapse show" id="widget-1">
                                <div class="widget-body">
                                    <div class="filter-items filter-items-count">
                                    @foreach($categories as $categories)
                                        <div class="filter-item">
                                            <label for="cat-1"><a href="../../produk/kategori[{{$categories->category_id}}]">{{$categories->nama_kategori}}</a></label>
                                            <!-- <span class="item-count">3</span> -->
                                        </div><!-- End .filter-item -->
                                    @endforeach
                                    </div><!-- End .filter-items -->
                                </div><!-- End .widget-body -->
                            </div><!-- End .collapse -->
                        </div><!-- End .widget -->
                    </div><!-- End .sidebar sidebar-shop -->
                </aside><!-- End .col-lg-3 -->
                <div class="col-lg-9">
                    <div class="toolbox">
                        <!-- <div class="toolbox-left">
                            <div class="toolbox-info">
                                Showing <span>9 of 56</span> Products
                            </div>
                        </div> -->

                        <!-- <div class="toolbox-right">
                            <div class="toolbox-sort">
                                <label for="sortby">Sort by:</label>
                                <div class="select-custom">
                                    <select name="sortby" id="sortby" class="form-control">
                                        <option value="popularity" selected="selected">Most Popular</option>
                                        <option value="rating">Most Rated</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>
                            </div>
                        </div> -->
                    </div><!-- End .toolbox -->

                    <div class="products mb-3">
                        <div class="row justify-content-center">
                            @foreach($products as $product)
                            <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                                <div class="product product-7 text-center">
                                    <figure class="product-media">
                                        <a href="../../lihat_produk/{{$product->product_id}}">
                                            <img src="../../asset/u_file/product_image/{{$product->product_image}}" alt="Product image" class="product-image">
                                        </a>

                                        <!-- <div class="product-action-vertical">
                                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                                            <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                                        </div> -->

                                        <!-- <div class="product-action">
                                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                                        </div> -->
                                    </figure><!-- End .product-media -->

                                    <div class="product-body">
                                        <div class="product-cat">
                                            <a href="../../produk/kategori[{{$categories->category_id}}]">{{$product->nama_kategori}}</a>
                                        </div><!-- End .product-cat -->

                                        <div class="mb-1"></div>

                                        <div class="product-cat">
                                            <a href="#"><b>{{$product->nama_merchant}}</b></a>
                                        </div><!-- End .product-cat -->

                                        <div class="mb-1"></div>

                                        <h3 class="product-title"><a href="../../lihat_produk/{{$product->product_id}}">{{$product->product_name}}</a></h3><!-- End .product-title -->

                                        <div class="mb-1"></div>

                                        <div class="product-price">
                                            <?php
                                                $harga_produk = "Rp " . number_format($product->price,2,',','.');     
                                                echo $harga_produk
                                            ?>
                                        </div><!-- End .product-price -->
                                        <!-- <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-val" style="width: 0%;"></div>
                                            </div>
                                            <span class="ratings-text">( 0 Reviews )</span>
                                        </div> -->
                                    </div><!-- End .product-body -->
                                </div><!-- End .product -->
                            </div><!-- End .col-sm-6 col-lg-4 col-xl-3 -->
                            @endforeach
                        </div><!-- End .row -->
                    </div><!-- End .products -->


                    <!-- <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link page-link-prev" href="#" aria-label="Previous" tabindex="-1" aria-disabled="true">
                                    <span aria-hidden="true"><i class="icon-long-arrow-left"></i></span>Prev
                                </a>
                            </li>
                            <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item-total">of 6</li>
                            <li class="page-item">
                                <a class="page-link page-link-next" href="#" aria-label="Next">
                                    Next <span aria-hidden="true"><i class="icon-long-arrow-right"></i></span>
                                </a>
                            </li>
                        </ul>
                    </nav> -->
                </div><!-- End .col-lg-9 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main><!-- End .main -->

@endsection