<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Invoice Pembelian</title>
        <meta name="keywords" content="HTML5 Template">
        <meta name="description" content="Marketplace Rumah Kreatif Toba">
        <meta name="author" content="p-themes">
        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="../asset/Image/logo_rkt.png">
        <!-- Plugins CSS File -->
        <link rel="stylesheet" href="../asset/Molla/assets/css/bootstrap.min.css">
        <!-- Main CSS File -->
        <link rel="stylesheet" href="../asset/Molla/assets/css/style.css">
    </head>

    <body>
        <center>
        <div class="tab-pane fade show active col-lg-8" id="tab-toko" role="tabpanel" aria-labelledby="tab-toko-link">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-dashboard">
                        <div class="card-body" align="center">
                            <p class=""><b>
                                @if($purchases->kode_pembelian == "")

                                @else
                                    {{$purchases->kode_pembelian}} - 
                                @endif
                                @if($profile->id == $purchases->user_id)
                                    {{$profile->name}}
                                @endif
                            </b></p>
                        </div><!-- End .card-body -->
                    </div><!-- End .card-dashboard -->
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            @foreach($product_purchases as $product_purchases)
                <a class="col-lg-12 border mb-1 row" style="padding: 15px 0px 15px 0px; margin: 0px">
                    <?php
                        $product_image = DB::table('product_images')->select('product_image_name')->where('product_id', $product_purchases->product_id)->orderBy('product_image_id', 'asc')->first();
                    ?>

                    <div class="col-md-2"  align="center">
                        <img src="../asset/u_file/product_image/{{$product_image->product_image_name}}" class="img-fluid" alt="{{$product_image->product_image_name}}" width="50px">
                    </div>
                    
                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                        <p class="text-muted mb-0">{{$product_purchases->product_name}}</p>
                    </div>
                    
                    <?php
                        $jumlah_product_specifications = DB::table('product_specifications')->where('product_id', $product_purchases->product_id)->count();
                    ?>
                    @if($jumlah_product_specifications == 0)

                    @else
                        @foreach($product_specifications as $product_specification)
                            @if($product_specification->product_id == $product_purchases->product_id)
                            <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                <p class="text-muted mb-0">{{$product_specification->nama_spesifikasi}}</p>
                            </div>
                            @endif
                        @endforeach
                    @endif

                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                        <p class="text-muted mb-0">Jumlah: {{$product_purchases->jumlah_pembelian_produk}}</p>
                    </div>

                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                        <?php
                            $harga_produk = "Rp " . number_format($product_purchases->price*$product_purchases->jumlah_pembelian_produk,0,',','.');
                        ?>
                        <p class="text-muted mb-0">{{$harga_produk}}</p>
                    </div>
                </a>
            @endforeach

            <div class="row">
                <aside class="col-lg-12">
                    <div class="summary">
                        <h3 class="summary-title">Detail Pembayaran</h3><!-- End .summary-title -->

                        <table class="table table-summary">
                            <!-- <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total</th>
                                </tr>
                            </thead> -->

                            <tbody>
                                <tr class="summary-subtotal">
                                    <td>Subtotal:</td>
                                    <td>
                                        <?php
                                            if($purchases->harga_pembelian == null){
                                                $invoice_total_harga_pembelian = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
                                                ->where('purchases.checkout_id', $purchases->checkout_id)
                                                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                                                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                                                ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();
            
                                                $invoice_total_harga_pembelian_fix = "Rp." . number_format($invoice_total_harga_pembelian->total_harga_pembelian,2,',','.');
                                            }
                                            
                                            else if($purchases->harga_pembelian != null){
                                                $invoice_total_harga_pembelian_fix = "Rp." . number_format(floor($purchases->harga_pembelian),2,',','.');
                                            }
                                        ?>
                                        <a>{{$invoice_total_harga_pembelian_fix}}</a>
                                    </td>
                                </tr>

                                @if($purchases->status_pembelian == "status1" || $purchases->status_pembelian == "status2" || $purchases->status_pembelian == "status3"
                                    || $purchases->status_pembelian == "status4" || $purchases->status_pembelian == "status5")
                                <tr class="summary-subtotal">
                                    <td>Ongkos Kirim [{{$courier_name}}] [{{$service_name}}]:</td>
                                    <td>
                                        <?php
                                            $invoice_ongkir = "Rp." . number_format($ongkir,2,',','.');
                                        ?>
                                        <a>{{$invoice_ongkir}}</a>
                                    </td>
                                </tr>
                                @endif
                                
                                <tr class="summary-total">
                                    <td>Total:</td>
                                    <td>
                                        <?php
                                            if($purchases->harga_pembelian == null){
                                                $invoice_total_pembelian = $total_harga->total_harga + $ongkir;
                                            }

                                            else if($purchases->harga_pembelian != null){
                                                $invoice_total_pembelian = $purchases->harga_pembelian + $ongkir;
                                            }
                                            $invoice_total_pembelian_fix = "Rp." . number_format($invoice_total_pembelian,2,',','.');
                                        ?>
                                        <a>{{$invoice_total_pembelian_fix}}</a>
                                    </td>
                                </tr><!-- End .summary-total -->
                            </tbody>
                        </table><!-- End .table table-summary -->
                    </div><!-- End .summary -->
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div><!-- .End .tab-pane -->
        </center>
    </body>
</html>