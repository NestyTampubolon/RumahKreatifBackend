<style>
    .menu li:hover>a, .menu li.show>a, .menu li.active>a{
        color: #800000;
    }

    .header-bottom .menu>li>a:before{
        background-color: #800000;
    }

    .header-intro-clearance .header-bottom .container::before{
        visibility: hidden;
    }

    .header-intro-clearance .header-bottom .container::after{
        visibility: hidden;
    }

    .header-right .wishlist>a:hover{
        color: #800000;
    }

    .header-right .cart-dropdown{
        padding: 0;
        margin-left: 1.5rem;
    }

    .header-right .cart-dropdown>a:hover{
        color: #800000;
    }

    .header-right .account{
        padding: 0;
        margin-left: 1.5rem;
    }
    
    .header-right .account>a:hover{
        color: #800000;
    }

    /* .icon:hover{
        color: #800000;
    } */
</style>

<header class="header header-2 header-intro-clearance">
    <div class="header-middle">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>

                <a href="{{ url('/') }}" class="logo" style="margin:0">
                    <img src="{{ URL::asset('asset/Image/logo_rkt.png') }}" alt="RKT Logo" width="65">
                </a>
                <!-- <h5 class="logo" style="color: #800000;">Rumah Kreatif Toba</h5> -->
            </div><!-- End .header-left -->

            <div class="header-center">
                <div class="header-search header-search-extended header-search-visible header-search-no-radius d-none d-lg-block">
                    <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                    <form action="#" method="get">
                        <div class="header-search-wrapper search-wrapper-wide">
                            <label for="q" class="sr-only">Search</label>
                            <input type="search" class="form-control" name="cari_produk" id="cari_produk"placeholder="Cari Produk ..." value="{{ old('cari_produk') }}" required>
                            <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
                        </div><!-- End .header-search-wrapper -->
                    </form>
                </div><!-- End .header-search -->
            </div>

            <div class="header-right">
                
            @if(Auth::check())
                <!-- <div class="wishlist">
                    <a href="wishlist.html" title="Wishlist">
                        <div class="icon">
                            <i class="icon-heart-o"></i>
                            <span class="wishlist-count badge">3</span>
                        </div>
                        <p>Keinginan</p>
                    </a>
                </div> -->

                <div class="cart-dropdown">
                    <a href="../keranjang" class="dropdown-toggle">
                        <div class="icon">
                            <i class="icon-shopping-cart"></i>
                            <!-- <span class="cart-count">2</span> -->
                        </div>
                        <p>Keranjang</p>
                    </a>
                </div><!-- End .cart-dropdown -->

                <!-- <div style="margin-left: 1.5rem;">
                    <p style="font-size: 3rem;">|</p>
                </div> -->
                <div class="wishlist">
                    <a href="{{ url('/dashboard') }}">
                        <div class="icon">
                            <i class="icon-user"></i>
                        </div>
                        <p>Akun</p>
                    </a>
                </div><!-- End .compare-dropdown -->
                
                @else
                <div class="wishlist">
                    <a href="#signin-modal" data-toggle="modal" title="My account">
                        <div class="icon">
                            <i class="icon-user"></i>
                        </div>
                        <p>Akun</p>
                    </a>
                </div><!-- End .compare-dropdown -->
                @endif
                
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-middle -->

    <div class="mb-1"></div>
    
    <div class="header-bottom sticky-header">
        <div class="container">
            <!-- <div class="header-left"></div> -->

            <div class="header-center">
                <nav class="main-nav">
                    <ul class="menu sf-arrows">
                        <!-- <li class="megamenu-container active">
                            <a href="index.html">Home</a>
                        </li> -->
                        <li class="megamenu-container">
                            <a href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="megamenu-container">
                            <a href="{{ url('/produk') }}">Produk</a>
                        </li>
                        <!-- <li>
                            <a href="category.html" class="sf-with-ul">Shop</a>

                            <div class="megamenu megamenu-md">
                                <div class="row no-gutters">
                                    <div class="col-md-8">
                                        <div class="menu-col">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="menu-title">Shop with sidebar</div>
                                                    <ul>
                                                        <li><a href="category-list.html">Shop List</a></li>
                                                        <li><a href="category-2cols.html">Shop Grid 2 Columns</a></li>
                                                        <li><a href="category.html">Shop Grid 3 Columns</a></li>
                                                        <li><a href="category-4cols.html">Shop Grid 4 Columns</a></li>
                                                        <li><a href="category-market.html"><span>Shop Market<span class="tip tip-new">New</span></span></a></li>
                                                    </ul>

                                                    <div class="menu-title">Shop no sidebar</div>
                                                    <ul>
                                                        <li><a href="category-boxed.html"><span>Shop Boxed No Sidebar<span class="tip tip-hot">Hot</span></span></a></li>
                                                        <li><a href="category-fullwidth.html">Shop Fullwidth No Sidebar</a></li>
                                                    </ul>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="menu-title">Product Category</div>
                                                    <ul>
                                                        <li><a href="product-category-boxed.html">Product Category Boxed</a></li>
                                                        <li><a href="product-category-fullwidth.html"><span>Product Category Fullwidth<span class="tip tip-new">New</span></span></a></li>
                                                    </ul>
                                                    <div class="menu-title">Shop Pages</div>
                                                    <ul>
                                                        <li><a href="cart.html">Cart</a></li>
                                                        <li><a href="checkout.html">Checkout</a></li>
                                                        <li><a href="wishlist.html">Wishlist</a></li>
                                                        <li><a href="dashboard.html">My Account</a></li>
                                                        <li><a href="#">Lookbook</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="banner banner-overlay">
                                            <a href="category.html" class="banner banner-menu">
                                                <img src="{{ URL::asset('asset/Molla/assets/images/menu/banner-1.jpg') }}" alt="Banner">

                                                <div class="banner-content banner-content-top">
                                                    <div class="banner-title text-white">Last <br>Chance<br><span><strong>Sale</strong></span></div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="sf-with-ul">Pages</a>

                            <ul>
                                <li>
                                    <a href="about.html" class="sf-with-ul">About</a>

                                    <ul>
                                        <li><a href="about.html">About 01</a></li>
                                        <li><a href="about-2.html">About 02</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="contact.html" class="sf-with-ul">Contact</a>

                                    <ul>
                                        <li><a href="contact.html">Contact 01</a></li>
                                        <li><a href="contact-2.html">Contact 02</a></li>
                                    </ul>
                                </li>
                                <li><a href="login.html">Login</a></li>
                                <li><a href="faq.html">FAQs</a></li>
                                <li><a href="404.html">Error 404</a></li>
                                <li><a href="coming-soon.html">Coming Soon</a></li>
                            </ul>
                        </li> -->
                    </ul>
                </nav>
            </div>

            <!-- <div class="header-right">
                
            </div> -->
        </div><!-- End .container -->
    </div><!-- End .header-bottom -->
    
    <div class="mb-1"></div>
    
</header><!-- End .header -->
