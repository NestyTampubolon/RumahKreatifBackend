@extends('user/layout/main')

@section('title', 'Rumah Kreatif Toba')

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<style>
    .intro-slider-container, .intro-slide{
        height:300px;
    }

    .rating{
        position: absolute;
        /* top:50%; */
        left: 50%;
        transform: translate(-50%, -50%) rotateY(180deg);
        display: flex;
    }

    .rating input{
        display: none;
    }

    .rating label{
        display: block;
        cursor: pointer;
        width: 30px;
        /*background: #ccc;*/
    }

    .rating label:before{
        content:'\f005';
        font-family: fontAwesome;
        position: relative;
        display: block;
        font-size: 30px;
        color: #101010;
    }

    .rating label:after{
        content:'\f005';
        font-family: fontAwesome;
        position: absolute;
        display: block;
        font-size: 30px;
        color: #ffc107;
        top:0;
        opacity: 0;
        transition: .5s;
    }

    .rating label:hover:after,
    .rating label:hover ~ label:after,
    .rating input:checked ~ label:after
    {
        opacity: 1;
    }

</style>

<meta name="csrf-token" content="{{ csrf_token() }}" />

@section('container')

<main class="main">
    <div class="page-content">
        <div class="product-details-top">
            <div class="bg-light pb-5 mb-4">
                <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
                    <div class="container d-flex align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/produk') }}">Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$product->product_name}}</li>
                        </ol>

                        <!-- <nav class="product-pager ml-auto" aria-label="Product">
                            <a class="product-pager-link product-pager-prev" href="#" aria-label="Previous" tabindex="-1">
                                <i class="icon-angle-left"></i>
                                <span>Prev</span>
                            </a>

                            <a class="product-pager-link product-pager-next" href="#" aria-label="Next" tabindex="-1">
                                <span>Next</span>
                                <i class="icon-angle-right"></i>
                            </a>
                        </nav> -->
                    </div>
                </nav>
                <div class="container">
                    <div class="product-gallery-carousel owl-carousel owl-full owl-nav-dark">
                        @foreach($product_images as $product_image)
                            @if($product_image->product_id == $product->product_id)
                            <figure class="product-gallery-image">
                                <img src="../asset/u_file/product_image/{{$product_image->product_image_name}}" data-zoom-image="../asset/u_file/product_image/{{$product_image->product_image_name}}" alt="product image">
                            </figure>
                            @endif
                        @endforeach

                        <!-- <figure class="product-gallery-image">
                            <img src="{{ URL::asset('asset/Molla/assets/images/products/single/gallery/2.jpg') }}" data-zoom-image="{{ URL::asset('asset/Molla/assets/images/products/single/gallery/2-big.jpg') }}" alt="product image">
                        </figure> -->

                    </div>
                </div>
            </div>

                <div class="product-details product-details-centered product-details-separator">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="product-cat">
                                    <a href="#"><b>{{$product->nama_merchant}}</b></a>
                                </div><!-- End .product-cat -->
                                
                                <div class="mb-2"></div>
                                
                                <h1 class="product-title">{{$product->product_name}}</h1>

                                <!-- <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-val" style="width: 80%;"></div>
                                    </div>
                                    <a class="ratings-text" href="#product-review-link" id="review-link">( 2 Reviews )</a>
                                </div> -->

                                <div class="product-price">
                                    <?php
                                        $harga_produk = "Rp " . number_format($product->price,2,',','.');     
                                        echo $harga_produk
                                    ?>
                                </div>

                                <div class="details-filter-row details-row-size mb-md-1">
                                    <div class="product-cat">
                                        <span>Kategori:</span>
                                        <a href="../produk/kategori[{{$product->category_id}}]">{{$product->nama_kategori}}</a>
                                    </div><!-- End .product-cat -->
                                    <span class="meta-separator">|</span>
                                    <?php
                                        $jumlah_product_specifications = DB::table('product_specifications')->where('product_id', $product->product_id)->count();
                                    ?>
                                    @if($jumlah_product_specifications == 0)

                                    @else
                                        @foreach($product_specifications as $product_specification)
                                            @if($product_specification->product_id == $product->product_id)
                                                <a>{{$product_specification->nama_spesifikasi}}</a>
                                                <span class="meta-separator">|</span>
                                            @endif
                                        @endforeach
                                    @endif
                                    <div class="product-cat">
                                        <span>Sisa:</span>
                                        <a>{{$stocks->stok}}</a>
                                    </div><!-- End .product-cat -->
                                </div><!-- End .entry-meta -->
                            </div>

                            <div class="col-md-6">
                                   
                            @if($stocks->stok > 0)
                                <!-- <div class="product-details-action">
                                    <div class="details-action-col"> -->
                                        @if(Auth::check())
                                            @if(!$merchant_address)
                                                <div class="product-details-action">
                                                    <div class="details-action-col">
                                                        <div class="product-details-quantity">
                                                            <a href="#pemberitahuan_alamat_toko" class="btn btn-primary" data-toggle="modal"><span>BELI SEKARANG</span></a>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary"><span>BELI SEKARANG</span></button>
                                                    </div>
                                                </div>
                                            @elseif($cek_alamat)
                                            <form action="../masuk_keranjang_beli/{{$product->product_id}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                                <div class="product-details-action">
                                                    <div class="details-action-col">
                                                        <div class="product-details-quantity">
                                                            <input type="number" id="qty" name="jumlah_pembelian_produk" class="form-control" value="1" min="1" max="{{$stocks->stok}}" step="1" data-decimals="0" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary"><span>BELI SEKARANG</span></button>
                                                    </div>
                                                </div>
                                            </form>
                                            @else
                                            <div class="product-details-action">
                                                <!-- <div class="details-action-col"> -->
                                                    <div class="product-details-quantity" style="margin-bottom:10px">
                                                        <input type="number" id="qty" class="form-control" value="1" min="1" max="{{$stocks->stok}}" step="1" data-decimals="0" required>
                                                    </div>
                                                    <form action="../alamat" method="get" enctype="multipart/form-data">
                                                    @csrf
                                                        <input type="number" name="product" value="{{$product->product_id}}" hidden>
                                                        <button type="submit" class="btn btn-primary"><span>BELI SEKARANG</span></button>
                                                    </form>
                                                    <!-- <a href="../alamat" class="btn btn-primary"><span>BELI SEKARANG</span></a> -->
                                                <!-- </div> -->
                                            </div>
                                            
                                            @endif
                                        @else
                                            <div class="product-details-action">
                                                <div class="details-action-col">
                                                    <div class="product-details-quantity">
                                                        <input type="number" id="qty" name="jumlah_pembelian_produk" class="form-control" value="1" min="1" max="{{$stocks->stok}}" step="1" data-decimals="0" required>
                                                    </div>
                                                    <a href="#signin-modal" class="btn btn-primary" data-toggle="modal" title="My account"><span>BELI SEKARANG</span></a>
                                                </div>
                                            </div>
                                        @endif

                                    <!-- </div> -->

                                    <!-- <div class="details-action-wrapper">
                                        <a href="#" class="btn-product btn-wishlist" title="Wishlist"><span>Add to Wishlist</span></a>
                                        <a href="#" class="btn-product btn-compare" title="Compare"><span>Add to Compare</span></a>
                                    </div> -->
                                <!-- </div> -->

                                <div class="product-details-action">
                                    <div class="details-action-col">
                                        @if(Auth::check())
                                            @if(!$merchant_address)
                                            <a href="#pemberitahuan_alamat_toko" class="btn btn-product btn-cart" data-toggle="modal"><span>tambah ke keranjang</span></a>
                                            @elseif($cek_alamat)
                                            <!-- <a href="../masuk_keranjang/{{$product->product_id}}" class="btn btn-product btn-cart"><span>tambah ke keranjang</span></a> -->
                                            <input type="checkbox" id="tambah_produk_keranjang" name="tambah_produk_keranjang" value="{{$product->product_id}}" hidden>
                                            <label class="btn btn-product btn-cart" for="tambah_produk_keranjang">
                                                <div><span>tambah ke keranjang</span></div>
                                            </label>
                                            @else
                                                <form action="../alamat" method="get" enctype="multipart/form-data">
                                                @csrf
                                                    <input type="number" name="product" value="{{$product->product_id}}" hidden>
                                                    <button type="submit" class="btn btn-product btn-cart"><span>tambah ke keranjang</span></button>
                                                </form>
                                                <!-- <a href="../alamat" class="btn btn-product btn-cart"><span>tambah ke keranjang</span></a> -->
                                            @endif
                                        @else
                                            <a href="#signin-modal" class="btn btn-product btn-cart" data-toggle="modal" title="My account"><span>tambah ke keranjang</span></a>
                                        @endif
                                    </div>
                                </div>
                                
                            @elseif($stocks->stok == 0)
                                <h6 style="color:darkred"><b>Maaf, Stok Telah Habis</b></h6>
                            @endif

                                <div class="product-details-footer details-footer-col">
                                    
                                    <!-- <div class="product-cat">
                                        <span>Category:</span>
                                        <a href="#">Women</a>,
                                        <a href="#">Dresses</a>,
                                        <a href="#">Yellow</a>
                                    </div> -->

                                    <!-- <div class="social-icons social-icons-sm">
                                        <span class="social-label">Share:</span>
                                        <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                        <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                        <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                        <a href="#" class="social-icon" title="Pinterest" target="_blank"><i class="icon-pinterest"></i></a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <div class="container">
            <div class="product-details-tab">
                <ul class="nav nav-pills justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-desc-link" data-toggle="tab" href="#product-desc-tab" role="tab" aria-controls="product-desc-tab" aria-selected="true">Deskripsi</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="product-info-link" data-toggle="tab" href="#product-info-tab" role="tab" aria-controls="product-info-tab" aria-selected="false">Additional information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-shipping-link" data-toggle="tab" href="#product-shipping-tab" role="tab" aria-controls="product-shipping-tab" aria-selected="false">Shipping & Returns</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" id="product-review-link" data-toggle="tab" href="#product-review-tab" role="tab" aria-controls="product-review-tab" aria-selected="false">Tinjauan ({{$jumlah_review}})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-info_toko-link" data-toggle="tab" href="#product-info_toko-tab" role="tab" aria-controls="product-info_toko-tab" aria-selected="false">Informasi Toko</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product-desc-tab" role="tabpanel" aria-labelledby="product-desc-link">
                        <div class="product-desc-content">
                        {{$product->product_description}}
                        </div>
                    </div>
                    <!-- <div class="tab-pane fade" id="product-info-tab" role="tabpanel" aria-labelledby="product-info-link">
                        <div class="product-desc-content">
                            <h3>Information</h3>
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec odio. Quisque volutpat mattis eros. Nullam malesuada erat ut turpis. Suspendisse urna viverra non, semper suscipit, posuere a, pede. Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. </p>

                            <h3>Fabric & care</h3>
                            <ul>
                                <li>Faux suede fabric</li>
                                <li>Gold tone metal hoop handles.</li>
                                <li>RI branding</li>
                                <li>Snake print trim interior </li>
                                <li>Adjustable cross body strap</li>
                                <li> Height: 31cm; Width: 32cm; Depth: 12cm; Handle Drop: 61cm</li>
                            </ul>

                            <h3>Size</h3>
                            <p>one size</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="product-shipping-tab" role="tabpanel" aria-labelledby="product-shipping-link">
                        <div class="product-desc-content">
                            <h3>Delivery & returns</h3>
                            <p>We deliver to over 100 countries around the world. For full details of the delivery options we offer, please view our <a href="#">Delivery information</a><br>
                            We hope you’ll love every purchase, but if you ever need to return an item you can do so within a month of receipt. For full details of how to make a return, please view our <a href="#">Returns information</a></p>
                        </div>
                    </div> -->
                    <div class="tab-pane fade" id="product-review-tab" role="tabpanel" aria-labelledby="product-review-link">
                        <div class="reviews">
                            <!-- <h3>Reviews</h3> -->
                            @foreach($reviews as $reviews)
                            <div class="review">
                                <div class="row no-gutters">
                                    <div class="col-auto">
                                        <h4><a href="#">{{$reviews->name}}</a></h4>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                @if($reviews->nilai_review == 5)
                                                <div class="ratings-val" style="width: 100%;"></div>
                                                @elseif($reviews->nilai_review == 4)
                                                <div class="ratings-val" style="width: 80%;"></div>
                                                @elseif($reviews->nilai_review == 3)
                                                <div class="ratings-val" style="width: 60%;"></div>
                                                @elseif($reviews->nilai_review == 2)
                                                <div class="ratings-val" style="width: 40%;"></div>
                                                @elseif($reviews->nilai_review == 1)
                                                <div class="ratings-val" style="width: 20%;"></div>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- <span class="review-date">6 days ago</span> -->
                                    </div>
                                    <div class="col">
                                        <!-- <h4>Good, perfect size</h4> -->

                                        <div class="review-content">
                                            <p>{{$reviews->isi_review}}</p>
                                        </div>

                                        <!-- <div class="review-action">
                                            <a href="#"><i class="icon-thumbs-up"></i>Helpful (2)</a>
                                            <a href="#"><i class="icon-thumbs-down"></i>Unhelpful (0)</a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <div class="review">
                                <div class="row no-gutters">
                                    <!-- <div class="col-auto">
                                        <h4><a href="#">John Doe</a></h4>
                                        <div class="ratings-container">
                                            <div class="ratings">
                                                <div class="ratings-val" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                        <span class="review-date">5 days ago</span>
                                    </div>
                                    <div class="col">
                                        <h4>Very good</h4>

                                        <div class="review-content">
                                            <p>Sed, molestias, tempore? Ex dolor esse iure hic veniam laborum blanditiis laudantium iste amet. Cum non voluptate eos enim, ab cumque nam, modi, quas iure illum repellendus, blanditiis perspiciatis beatae!</p>
                                        </div>

                                        <div class="review-action">
                                            <a href="#"><i class="icon-thumbs-up"></i>Helpful (0)</a>
                                            <a href="#"><i class="icon-thumbs-down"></i>Unhelpful (0)</a>
                                        </div>
                                    </div> -->
                                    
                                    @if(Auth::check())
                                        @if(!$cek_review)
                                        <div class="rating">
                                            <input type="radio" id="star1" onclick="rate5()"><label for="star1"></label>
                                            <input type="radio" id="star2" onclick="rate4()"><label for="star2"></label>
                                            <input type="radio" id="star3" onclick="rate3()"><label for="star3"></label>
                                            <input type="radio" id="star4" onclick="rate2()"><label for="star4"></label>
                                            <input type="radio" id="star5" onclick="rate1()"><label for="star5"></label>
                                        </div>
                                        <script>
                                            function rate1() {
                                                $("#rate").modal();
                                                document.getElementById("star5-modal").checked = true;
                                            }
                                            function rate2() {
                                                $("#rate").modal();
                                                document.getElementById("star4-modal").checked = true;
                                            }
                                            function rate3() {
                                                $("#rate").modal();
                                                document.getElementById("star3-modal").checked = true;
                                            }
                                            function rate4() {
                                                $("#rate").modal();
                                                document.getElementById("star2-modal").checked = true;
                                            }
                                            function rate5() {
                                                $("#rate").modal();
                                                document.getElementById("star1-modal").checked = true;
                                            }
                                        </script>
                                        @else

                                        @endif
                                    @else

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="product-info_toko-tab" role="tabpanel" aria-labelledby="product-info_toko-link">
                        <div class="product-info_toko-content">
                            <table>
                                <tr>
                                    <td>Nama Toko</td>
                                    <td>&emsp; : &emsp;</td>
                                    <td>{{$product->nama_merchant}}</td>
                                </tr>
                                <tr>
                                    <td>Deskripsi Toko</td>
                                    <td>&emsp; : &emsp;</td>
                                    <td>{{$product->deskripsi_toko}}</td>
                                </tr>
                                <tr>
                                    <td>Kontak Toko</td>
                                    <td>&emsp; : &emsp;</td>
                                    <td>{{$product->kontak_toko}}</td>
                                </tr>
                                <tr>
                                    <td>Alamat Toko</td>
                                    <td>&emsp; : &emsp;</td>
                                    <td>{{$merchant_address->merchant_street_address}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- <div class="container">
            <h2 class="title text-center mb-4">You May Also Like</h2>
            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl" 
                data-owl-options='{
                    "nav": false, 
                    "dots": true,
                    "margin": 20,
                    "loop": false,
                    "responsive": {
                        "0": {
                            "items":1
                        },
                        "480": {
                            "items":2
                        },
                        "768": {
                            "items":3
                        },
                        "992": {
                            "items":4
                        },
                        "1200": {
                            "items":4,
                            "nav": true,
                            "dots": false
                        }
                    }
                }'>
                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <span class="product-label label-new">New</span>
                        <a href="product.html">
                            <img src="{{ URL::asset('asset/Molla/assets/images/products/product-4.jpg') }}" alt="Product image" class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                            <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                        </div>

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div>
                    </figure>

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Women</a>
                        </div>
                        <h3 class="product-title"><a href="product.html">Brown paperbag waist <br>pencil skirt</a></h3>
                        <div class="product-price">
                            $60.00
                        </div>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 20%;"></div>
                            </div>
                            <span class="ratings-text">( 2 Reviews )</span>
                        </div>

                        <div class="product-nav product-nav-dots">
                            <a href="#" class="active" style="background: #cc9966;"><span class="sr-only">Color name</span></a>
                            <a href="#" style="background: #7fc5ed;"><span class="sr-only">Color name</span></a>
                            <a href="#" style="background: #e8c97a;"><span class="sr-only">Color name</span></a>
                        </div>
                    </div>
                </div>

                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <span class="product-label label-out">Out of Stock</span>
                        <a href="product.html">
                            <img src="{{ URL::asset('asset/Molla/assets/images/products/product-6.jpg') }}" alt="Product image" class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                            <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                        </div>

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div>
                    </figure>

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Jackets</a>
                        </div>
                        <h3 class="product-title"><a href="product.html">Khaki utility boiler jumpsuit</a></h3>
                        <div class="product-price">
                            <span class="out-price">$120.00</span>
                        </div>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 80%;"></div>
                            </div>
                            <span class="ratings-text">( 6 Reviews )</span>
                        </div>
                    </div>
                </div>

                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <span class="product-label label-top">Top</span>
                        <a href="product.html">
                            <img src="{{ URL::asset('asset/Molla/assets/images/products/product-11.jpg') }}" alt="Product image" class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                            <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                        </div>

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div>
                    </figure>

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Shoes</a>
                        </div>
                        <h3 class="product-title"><a href="product.html">Light brown studded Wide fit wedges</a></h3>
                        <div class="product-price">
                            $110.00
                        </div>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 80%;"></div>
                            </div>
                            <span class="ratings-text">( 1 Reviews )</span>
                        </div>

                        <div class="product-nav product-nav-dots">
                            <a href="#" class="active" style="background: #8b513d;"><span class="sr-only">Color name</span></a>
                            <a href="#" style="background: #333333;"><span class="sr-only">Color name</span></a>
                            <a href="#" style="background: #d2b99a;"><span class="sr-only">Color name</span></a>
                        </div>
                    </div>
                </div>

                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <a href="product.html">
                            <img src="{{ URL::asset('asset/Molla/assets/images/products/product-10.jpg') }}" alt="Product image" class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                            <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                        </div>

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div>
                    </figure>

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Jumpers</a>
                        </div>
                        <h3 class="product-title"><a href="product.html">Yellow button front tea top</a></h3>
                        <div class="product-price">
                            $56.00
                        </div>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 0%;"></div>
                            </div>
                            <span class="ratings-text">( 0 Reviews )</span>
                        </div>
                    </div>
                </div>

                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <a href="product.html">
                            <img src="{{ URL::asset('asset/Molla/assets/images/products/product-7.jpg" alt="') }}Product image" class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview" title="Quick view"><span>Quick view</span></a>
                            <a href="#" class="btn-product-icon btn-compare" title="Compare"><span>Compare</span></a>
                        </div>

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div>
                    </figure>

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Jeans</a>
                        </div>
                        <h3 class="product-title"><a href="product.html">Blue utility pinafore denim dress</a></h3>
                        <div class="product-price">
                            $76.00
                        </div>
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 20%;"></div>
                            </div>
                            <span class="ratings-text">( 2 Reviews )</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</main>

<div class="modal fade" id="rate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="icon-close"></i></span>
                </button>

                <div class="form-box">
                    <div class="tab-content" id="tab-content-5">
                        <div class="tab-pane fade show active">
                            <form action="../PostTinjauan/{{$product->product_id}}" method="post">
                            @csrf
                                <div class="rating">
                                    <input type="radio" name="nilai_review" id="star1-modal" value="5"><label for="star1"></label>
                                    <input type="radio" name="nilai_review" id="star2-modal" value="4"><label for="star2"></label>
                                    <input type="radio" name="nilai_review" id="star3-modal" value="3"><label for="star3"></label>
                                    <input type="radio" name="nilai_review" id="star4-modal" value="2"><label for="star4"></label>
                                    <input type="radio" name="nilai_review" id="star5-modal" value="1"><label for="star5"></label>
                                </div><br>
                                
                                <div class="form-group">
                                    <label for="isi_review">Isi Tinjauan *</label>
                                    <input type="text" class="form-control" id="isi_review" name="isi_review" placeholder="Berikan pendapat Anda" required>
                                </div><!-- End .form-group -->

                                <div class="form-footer">
                                    <button type="submit" class="btn btn-outline-primary-2 btn-round">
                                        <span>KIRIM</span>
                                    </button>
                                </div><!-- End .form-footer -->
                            </form>
                        </div><!-- .End .tab-pane -->
                    </div><!-- End .tab-content -->
                </div><!-- End .form-box -->
            </div><!-- End .modal-body -->
        </div><!-- End .modal-content -->
    </div><!-- End .modal-dialog -->
</div><!-- End .modal -->

<div class="modal fade" id="pemberitahuan_alamat_toko" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="icon-close"></i></span>
                </button>

                <div class="form-box">
                    <h5>Mohon maaf. <br><br> Anda tidak dapat membeli dari toko ini dikarenakan toko belum memasukkan alamat.</h5>
                </div><!-- End .form-box -->
            </div><!-- End .modal-body -->
        </div><!-- End .modal-content -->
    </div><!-- End .modal-dialog -->
</div><!-- End .modal -->

<div class="modal fade" id="tambah_produk_keranjang_modal" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="icon-close"></i></span>
                </button>

                <div class="form-box">
                    <h5>Produk telah ditambahkan ke keranjang.</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{ URL::asset('asset/js/function_2.js') }}"></script>

@endsection