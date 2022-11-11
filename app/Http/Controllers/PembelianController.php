<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class PembelianController extends Controller
{
    public function checkout($merchant_id) {
        date_default_timezone_set('Asia/Jakarta');

        // set logout saat habis session dan auth
        if(!Auth::check()){
            return redirect("./logout");
        }

        $user_id = Auth::user()->id;

        $product_id = $_POST['product_id'];

        $jumlah_dipilih = count($product_id);
        
        for($x=0; $x<$jumlah_dipilih; $x++){
            $product = DB::table('products')->where('product_id', $product_id[$x])->first();
            $stocks = DB::table('stocks')->where('product_id', $product_id[$x])->first();
            if($stocks->stok > 0){
                $jumlah_masuk_keranjang = $_POST['jumlah_masuk_keranjang'];
                DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_id[$x])->update([
                    'jumlah_masuk_keranjang' => $jumlah_masuk_keranjang[$x],
                ]);
            }
            
            else if($stocks->stok == 0){
                DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_id[$x])->delete();
            }
        }

        $cek_cart = DB::table('carts')->where('user_id', $user_id)->where('merchant_id', $merchant_id)
        ->join('products', 'carts.product_id', '=', 'products.product_id')->count();

        $carts = DB::table('carts')->where('user_id', $user_id)->where('merchant_id', $merchant_id)
        ->join('products', 'carts.product_id', '=', 'products.product_id')->get();

        $total_harga = DB::table('carts')->select(DB::raw('SUM(price * jumlah_masuk_keranjang) as total_harga'))->where('user_id', $user_id)
        ->where('merchant_id', $merchant_id)->join('products', 'carts.product_id', '=', 'products.product_id')->first();


        // foreach($carts as $cek_cart_voucher){
        //     $cek_pembelian_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
        //     ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('minimal_pengambilan', '<', $total_harga->total_harga)
        //     ->where('target_kategori', $cek_cart_voucher->category_id)->where('tipe_voucher', "pembelian")
        //     ->orderBy('nama_voucher', 'asc')->count();

        // }
        
        $get_pembelian_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
        ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('minimal_pengambilan', '<=', $total_harga->total_harga)
        ->where('tipe_voucher', "pembelian")->orderBy('nama_voucher', 'asc')->get();
        
        $cek_ongkos_kirim_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
        ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('minimal_pengambilan', '<=', $total_harga->total_harga)
        ->where('tipe_voucher', "ongkos_kirim")->orderBy('nama_voucher', 'asc')->count();

        $get_ongkos_kirim_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
        ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('minimal_pengambilan', '<=', $total_harga->total_harga)
        ->where('tipe_voucher', "ongkos_kirim")->orderBy('nama_voucher', 'asc')->get();

        $vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
        ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->orderBy('nama_voucher', 'asc')->get();
        

        // $pembelian_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
        // ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('tipe_voucher', "pembelian")->orderBy('nama_voucher', 'asc')->get();

        $ongkos_kirim_vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '<=', date('Y-m-d'))
        ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->where('tipe_voucher', "ongkos_kirim")->orderBy('nama_voucher', 'asc')->get();
        
        $total_berat = DB::table('carts')->select(DB::raw('SUM(heavy) as total_berat'))->where('user_id', $user_id)->where('merchant_id', $merchant_id)
        ->join('products', 'carts.product_id', '=', 'products.product_id')->first();
        
        $merchant_address = DB::table('merchant_address')->where('merchant_id', $merchant_id)->first();

        $user_address = DB::table('user_address')->where('user_id', $user_id)->where('is_deleted', 0)->orderBy('subdistrict_id', 'asc')->get();

        return view('user.pembelian.checkout')->with('merchant_id', $merchant_id)->with('cek_cart', $cek_cart)->with('carts', $carts)
        ->with('ongkos_kirim_vouchers', $ongkos_kirim_vouchers)
        ->with('vouchers', $vouchers)->with('total_harga', $total_harga)->with('total_berat', $total_berat)
        ->with('merchant_address', $merchant_address)->with('user_address', $user_address)->with('get_pembelian_vouchers', $get_pembelian_vouchers)
        ->with('cek_ongkos_kirim_vouchers', $cek_ongkos_kirim_vouchers)->with('get_ongkos_kirim_vouchers', $get_ongkos_kirim_vouchers);
    }
    
    public function ambil_voucher_pembelian() {
        $user_id = Auth::user()->id;
        $voucher = $_GET['voucher'];
        $merchant_id = $_GET['merchant_id'];
        $total_harga_checkout = $_GET['total_harga_checkout'];
        
        $voucher_dipilih = DB::table('vouchers')->where('voucher_id', $voucher)->first();

        $target_kategori = explode(",", $voucher_dipilih->target_kategori);

        foreach($target_kategori as $target_kategori){
            $subtotal_harga_produk = DB::table('carts')->select(DB::raw('SUM(price * jumlah_masuk_keranjang) as subtotal_harga_produk'))->where('category_id', $target_kategori)
            ->where('user_id', $user_id)->where('merchant_id', $merchant_id)->join('products', 'carts.product_id', '=', 'products.product_id')->first();

            $potongan_subtotal[] = (int)$subtotal_harga_produk->subtotal_harga_produk * $voucher_dipilih->potongan / 100;
        }
        
        $jumlah_potongan_subtotal = array_sum($potongan_subtotal);

        if($jumlah_potongan_subtotal <= $voucher_dipilih->maksimal_pemotongan){
            $potongan = $jumlah_potongan_subtotal;
        }

        else if($jumlah_potongan_subtotal > $voucher_dipilih->maksimal_pemotongan){
            $potongan = $voucher_dipilih->maksimal_pemotongan;
        }

        if($potongan > $voucher_dipilih->maksimal_pemotongan){
            $potongan = $voucher_dipilih->maksimal_pemotongan;
        }

        $total_harga_fix = (int)$total_harga_checkout - $potongan;

        $total_harga_checkout = "Rp." . number_format($total_harga_fix,0,',','.');
        
        return response()->json($total_harga_fix);
    }

    public function ambil_voucher_ongkos_kirim() {
        $user_id = Auth::user()->id;
        $voucher_ongkir = $_GET['voucher_ongkir'];
        
        $voucher_dipilih = DB::table('vouchers')->where('voucher_id', $voucher_ongkir)->first();

        $potongan = $voucher_dipilih->potongan;
        
        return response()->json($potongan);
    }
    
    public function pilih_metode_pembelian(Request $request) {
        $tipe = $_GET['tipe'];
        
        return response()->json($tipe);
    }

    public function ambil_jalan(Request $request) {
        $curl = curl_init();

        $user_id = Auth::user()->id;
        $user_address_id = $_GET['id'];
        $user_address = DB::table('user_address')->where('user_id', $user_id)->where('user_address_id', $user_address_id)->first();
        $param = $user_address->city_id;
        $subdistrict_id = $user_address->subdistrict_id;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=".$param,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array("key: 41df939eff72c9b050a81d89b4be72ba"),
        ));

        $response = curl_exec($curl);
        $collection = json_decode($response, true);
        $filters =  array_filter($collection['rajaongkir']['results'], function($r) use ($subdistrict_id) {
            return $r['subdistrict_id'] == $subdistrict_id;
        });
        
        foreach ($filters as $filter){
            $filtered = $filter;
        }
        
        $err = curl_error($curl);
        curl_close($curl);

        return response()->json($filtered);
    }

    public function cek_ongkir(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=$request->origin&originType=$request->originType&destination=$request->destination&destinationType=$request->destinationType&weight=$request->weight&courier=$request->courier",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: 41df939eff72c9b050a81d89b4be72ba"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        return response()->json($response);
    }

    public function PostBeliProduk(Request $request) {
        $user_id = Auth::user()->id;
        
        $merchant_id = $request -> merchant_id;
        
        $voucher_pembelian = $request -> voucher_pembelian;
        $voucher_ongkos_kirim = $request -> voucher_ongkos_kirim;
        
        $metode_pembelian = $request -> metode_pembelian;
        $alamat_purchase = $request -> alamat_purchase;
        $jumlah_pembelian_produk = $request -> jumlah_pembelian_produk;
        
        $courier_code = $request -> courier;
        $service = $request -> service;
        
        DB::table('checkouts')->insert([
            'user_id' => $user_id,
        ]);

        $checkout_id = DB::table('checkouts')->select('checkout_id')->orderBy('checkout_id', 'desc')->first();

        if($voucher_pembelian){
            DB::table('claim_vouchers')->insert([
                'checkout_id' => $checkout_id->checkout_id,
                'voucher_id' => $voucher_pembelian,
            ]);
        }

        if($voucher_ongkos_kirim){
            DB::table('claim_vouchers')->insert([
                'checkout_id' => $checkout_id->checkout_id,
                'voucher_id' => $voucher_ongkos_kirim,
            ]);
        }

        if($metode_pembelian == "ambil_ditempat"){
            DB::table('purchases')->insert([
                'user_id' => $user_id,
                'checkout_id' => $checkout_id->checkout_id,
                'alamat_purchase' => "",
                'status_pembelian' => "status1_ambil",
                'ongkir' => 0,
            ]);
        }
        
        if($metode_pembelian == "pesanan_dikirim"){
            $ongkir = $request -> ongkir;
            DB::table('purchases')->insert([
                'user_id' => $user_id,
                'checkout_id' => $checkout_id->checkout_id,
                'alamat_purchase' => $alamat_purchase,
                'status_pembelian' => "status1",
                'courier_code' => $courier_code,
                'service' => $service,
                'ongkir' => $ongkir,
            ]);
        }
        
        
        $purchase_id = DB::table('purchases')->select('purchase_id')->orderBy('purchase_id', 'desc')->first();

        $product_purchase = DB::table('carts')->select('carts.product_id', 'jumlah_masuk_keranjang')->where('user_id', $user_id)
        ->where('merchant_id', $merchant_id)->join('products', 'carts.product_id', '=', 'products.product_id')->get();

        foreach($product_purchase as $product_purchase){
            DB::table('product_purchases')->insert([
                'purchase_id' => $purchase_id->purchase_id,
                'product_id' => $product_purchase->product_id,
                'jumlah_pembelian_produk' => $product_purchase->jumlah_masuk_keranjang,
            ]);
            
            $stok = DB::table('stocks')->select('stok')->where('product_id', $product_purchase->product_id)->first();

            DB::table('stocks')->where('product_id', $product_purchase->product_id)->update([
                'stok' => $stok->stok - $product_purchase->jumlah_masuk_keranjang,
            ]);
            
            DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_purchase->product_id)->delete();
        }

        return redirect('../daftar_pembelian');
    }

    public function daftar_pembelian() {
        // set logout saat habis session dan auth
        if(!Auth::check()){
            return redirect("./logout");
        }
        
        if(Session::get('toko')){
            $toko = Session::get('toko');

            $cek_purchase = DB::table('product_purchases')->select('product_purchases.purchase_id')->where('merchant_id', $toko)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.purchase_id', 'desc')->groupBy('purchase_id')->get();

            $jumlah_purchases = DB::table('product_purchases')->where('merchant_id', $toko)
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchase_id')->count();
            
            $profiles = DB::table('profiles')->join('users', 'profiles.user_id', '=', 'users.id')->get();
            
            $purchases = DB::table('purchases')->join('users', 'purchases.user_id', '=', 'users.id')->orderBy('purchase_id', 'desc')->get();

            $product_purchases = DB::table('product_purchases')->where('merchant_id', $toko)->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            $product_specifications = DB::table('product_specifications')
            ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
            ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
            ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
            
            $count_proof_of_payment = DB::table('proof_of_payments')->select(DB::raw('COUNT(*) as count_proof_of_payment'))->first();

            $proof_of_payments = DB::table('proof_of_payments')->get();

            return view('user.toko.daftar_pembelian')->with('cek_purchase', $cek_purchase)->with('jumlah_purchases', $jumlah_purchases)->with('purchases', $purchases)
            ->with('product_purchases', $product_purchases)->with('product_specifications', $product_specifications)
            ->with('proof_of_payments', $proof_of_payments)->with('profiles', $profiles)->with('count_proof_of_payment', $count_proof_of_payment);
        }

        else{
            $user_id = Auth::user()->id;

            $cek_admin_id = DB::table('users')->where('id', $user_id)->where('is_admin', 1)->first();

            if($cek_admin_id){
                $checkouts = DB::table('checkouts')->join('users', 'checkouts.user_id', '=', 'users.id')->orderBy('checkout_id', 'desc')->get();
                
                // $claim_vouchers = DB::table('claim_vouchers')->where('tipe_voucher', 'pembelian')->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->get();

                $claim_pembelian_vouchers = DB::table('claim_vouchers')->where('tipe_voucher', 'pembelian')->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->get();
                
                $claim_ongkos_kirim_vouchers = DB::table('claim_vouchers')->where('tipe_voucher', 'ongkos_kirim')->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->get();
                
                $purchases = DB::table('purchases')->join('users', 'purchases.user_id', '=', 'users.id')->orderBy('purchase_id', 'desc')->get();
                
                $profiles = DB::table('profiles')->get();

                $product_purchases = DB::table('product_purchases')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

                $product_specifications = DB::table('product_specifications')
                ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
                ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
                ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
        
                return view('admin.daftar_pembelian')->with('checkouts', $checkouts)->with('claim_pembelian_vouchers', $claim_pembelian_vouchers)
                ->with('claim_ongkos_kirim_vouchers', $claim_ongkos_kirim_vouchers)->with('product_purchases', $product_purchases)
                ->with('profiles', $profiles)->with('product_specifications', $product_specifications)->with('purchases', $purchases);
            }

            else{
                $checkouts = DB::table('checkouts')->where('user_id', $user_id)
                ->join('users', 'checkouts.user_id', '=', 'users.id')->orderBy('checkout_id', 'desc')->get();
                
                $claim_vouchers = DB::table('claim_vouchers')->where('tipe_voucher', 'pembelian')->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->get();
                
                $purchases = DB::table('purchases')->where('user_id', $user_id)
                ->join('users', 'purchases.user_id', '=', 'users.id')->orderBy('purchase_id', 'desc')->get();
                

                


                // foreach($claim_vouchers as $claim_vouchers_get){
                //     $target_kategori = explode(",", $claim_vouchers_get->target_kategori);
                    
                //     foreach($target_kategori as $target_kategori){
                //         foreach($purchases as $purchases_get){
                //         $subtotal_harga_produk = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
                //         ->where('purchases.checkout_id', $purchases_get->checkout_id)->where('category_id', $target_kategori)
                //         ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                //         ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                //         ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->get();

                //             foreach($subtotal_harga_produk as $subtotal_harga_produk_get){
                //                 $potongan_subtotal[] = (int)$subtotal_harga_produk_get->total_harga_pembelian * $claim_vouchers_get->potongan / 100;
                //             }
                //         }
                //         $jumlah_potongan_subtotal = array_sum($potongan_subtotal);
                //         dd($jumlah_potongan_subtotal);
                //     }
                // }






                $cek_purchases = DB::table('purchases')->where('user_id', $user_id)->first();
                
                $profile = DB::table('profiles')->where('user_id', $user_id)
                ->join('users', 'profiles.user_id', '=', 'users.id')->first();
                
                $product_purchases = DB::table('product_purchases')->where('user_id', $user_id)
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

                $product_specifications = DB::table('product_specifications')
                ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
                ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
                ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
                
                $count_proof_of_payment = DB::table('proof_of_payments')->select(DB::raw('COUNT(*) as count_proof_of_payment'))->first();
        
                return view('user.pembelian.daftar_pembelian')->with('checkouts', $checkouts)->with('claim_vouchers', $claim_vouchers)
                ->with('purchases', $purchases)->with('cek_purchases', $cek_purchases)->with('product_purchases', $product_purchases)->with('profile', $profile)
                ->with('product_specifications', $product_specifications)->with('count_proof_of_payment', $count_proof_of_payment);
            }
        }
    }

    public function detail_pembelian($purchase_id) {
        // set logout saat habis session dan auth
        if(!Auth::check()){
            return redirect("./logout");
        }
        
        if(Session::get('toko')){
            $toko = Session::get('toko');
            
            $cek_purchase = DB::table('purchases')->where('purchase_id', $purchase_id)->where('status_pembelian', 'status2')
            ->join('users', 'purchases.user_id', '=', 'users.id')->first();

            $purchase = DB::table('purchases')->where('purchase_id', $purchase_id)->join('users', 'purchases.user_id', '=', 'users.id')->first();

            $profile = DB::table('profiles')->where('user_id', $purchase->user_id)->join('users', 'profiles.user_id', '=', 'users.id')->first();

            $product_purchases = DB::table('product_purchases')->where('merchant_id', $toko)->where('product_purchases.purchase_id', $purchase_id)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            $product_specifications = DB::table('product_specifications')
            ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
            ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
            ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
            
            $total_harga = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga'))
            ->where('product_purchases.purchase_id', $purchase_id)->join('products', 'product_purchases.product_id', '=', 'products.product_id')->first();
            
            $cek_proof_of_payment = DB::table('proof_of_payments')->where('purchase_id', $purchase_id)->first();



            // $checkouts = DB::table('checkouts')->where('user_id', $user_id)
            // ->join('users', 'checkouts.user_id', '=', 'users.id')->orderBy('checkout_id', 'desc')->get();
            
            // $claim_vouchers = DB::table('claim_vouchers')->get();

            $purchases = DB::table('purchases')->where('purchase_id', $purchase_id)->join('users', 'purchases.user_id', '=', 'users.id')->first();

            $profile = DB::table('profiles')->where('user_id', $purchases->user_id)->join('users', 'profiles.user_id', '=', 'users.id')->first();

            $purchases_address = DB::table('user_address')->where('user_id', $purchases->user_id)->first();

            $merchant_purchase = DB::table('product_purchases')->select('merchant_id')->where('purchase_id', $purchase_id)
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('merchant_id')->first();

            $cek_merchant_address = DB::table('merchant_address')->where('merchant_id', $merchant_purchase->merchant_id)->count();

            $merchant_address = DB::table('merchant_address')->where('merchant_id', $merchant_purchase->merchant_id)->first();


            $curl = curl_init();
            
            $param = $merchant_address->city_id;
            $subdistrict_id = $merchant_address->subdistrict_id;
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=".$param,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array("key: 41df939eff72c9b050a81d89b4be72ba"),
            ));

            $response = curl_exec($curl);
            $collection = json_decode($response, true);
            $filters =  array_filter($collection['rajaongkir']['results'], function($r) use ($subdistrict_id) {
                return $r['subdistrict_id'] == $subdistrict_id;
            });
            
            foreach ($filters as $filter){
                $lokasi_toko = $filter;
            }
            
            $err = curl_error($curl);
            curl_close($curl);
            

            $cek_user_address = DB::table('purchases')->where('purchases.user_id', $purchases->user_id)->where('purchase_id', $purchase_id)
            ->join('user_address', 'purchases.alamat_purchase', '=', 'user_address.user_address_id')->count();

            $user_address = DB::table('purchases')->where('purchases.user_id', $purchases->user_id)->where('purchase_id', $purchase_id)
            ->join('user_address', 'purchases.alamat_purchase', '=', 'user_address.user_address_id')->first();

            $curl_2 = curl_init();

            if($cek_user_address != 0){
                $param = $user_address->city_id;
                $subdistrict_id = $user_address->subdistrict_id;
                
                curl_setopt_array($curl_2, array(
                    CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=".$param,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array("key: 41df939eff72c9b050a81d89b4be72ba"),
                ));

                $response = curl_exec($curl_2);
                $collection = json_decode($response, true);
                $filters =  array_filter($collection['rajaongkir']['results'], function($r) use ($subdistrict_id) {
                    return $r['subdistrict_id'] == $subdistrict_id;
                });
                
                foreach ($filters as $filter){
                    $lokasi_pembeli = $filter;
                }
                
                $err = curl_error($curl_2);
                curl_close($curl_2);                

                $total_berat = DB::table('product_purchases')->select(DB::raw('SUM(heavy) as total_berat'))->where('user_id', $purchases->user_id)->where('product_purchases.purchase_id', $purchase_id)
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->first();


                $curl_3 = curl_init();
                
                $param = $merchant_address->city_id;
                $merchant_subdistrict_id = $merchant_address->subdistrict_id;
                $purchases_subdistrict_id = $user_address->subdistrict_id;
                $courier_code = $purchases->courier_code;
                $service = $purchases->service;

                curl_setopt_array($curl_3, array(
                    CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "origin=$purchases_subdistrict_id&originType=subdistrict&destination=$merchant_subdistrict_id&destinationType=subdistrict&weight=$total_berat->total_berat&courier=$purchases->courier_code",
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/x-www-form-urlencoded",
                        "key: 41df939eff72c9b050a81d89b4be72ba"
                    ),
                ));
        
                $response = curl_exec($curl_3);
                $collection = json_decode($response, true);                

                if($courier_code != "" || $service != ""){
                    // $filters =  array_filter($collection['rajaongkir']['results'], function($r) use ($courier_code) {
                    //     return $r['code'] == $courier_code;
                    // });
                    // foreach ($filters as $filter){
                    //     $courier_array = $filter;
                    // }
                    
                    // $filters2 =  array_filter($courier_array['costs'], function($s) use ($service){
                    //     return $s['service'] == $service;
                    // });
                    // foreach ($filters2 as $filter2){
                    //     $service_array = $filter2;
                    // }

                    // $filters3 =  array_filter($service_array['cost']);
                    // foreach ($filters3 as $filter3){
                    //     $ongkir = $filter3;
                    // }

                    if($courier_code == "pos"){ $courier_name = "POS Indonesia (POS)"; }
                    else if($courier_code == "jne"){ $courier_name = "Jalur Nugraha Eka (JNE)"; }

                    $service_name = $service;
                    
                    $ongkir = $purchases->ongkir;

                    // $courier_name = $courier_array["name"];
                    // $service_name = $service_array["description"];
                }
                
                else{
                    $ongkir = 0;
                    $courier_name = "";
                    $service_name = "";
                }

                $err = curl_error($curl_3);
                curl_close($curl_3);
                
            }

            else if($cek_user_address == 0){
                $lokasi_pembeli = "";
                $ongkir = 0;
                $courier_name = "";
                $service_name = "";
            }

            // dd($user_address);


            return view('user.toko.detail_pembelian')->with('product_purchases', $product_purchases)->with('product_specifications', $product_specifications)
            ->with('purchases', $purchase)->with('cek_proof_of_payment', $cek_proof_of_payment)->with('profile', $profile)->with('total_harga', $total_harga)
            ->with('cek_merchant_address', $cek_merchant_address)->with('merchant_address', $merchant_address)->with('lokasi_toko', $lokasi_toko)
            ->with('cek_user_address', $cek_user_address)->with('user_address', $user_address)->with('lokasi_pembeli', $lokasi_pembeli)
            ->with('ongkir', $ongkir)->with('courier_name', $courier_name)->with('service_name', $service_name);
        }


        else{
            $user_id = Auth::user()->id;

            $cek_admin_id = DB::table('users')->where('id', $user_id)->where('is_admin', 1)->first();

            if($cek_admin_id){

            }

            else{

                // $checkouts = DB::table('checkouts')->where('user_id', $user_id)
                // ->join('users', 'checkouts.user_id', '=', 'users.id')->orderBy('checkout_id', 'desc')->get();

                $profile = DB::table('profiles')->where('user_id', $user_id)->join('users', 'profiles.user_id', '=', 'users.id')->first();
                
                $purchases = DB::table('purchases')->where('user_id', $user_id)->where('purchase_id', $purchase_id)->join('users', 'purchases.user_id', '=', 'users.id')->first();
                
                $claim_pembelian_vouchers = DB::table('claim_vouchers')->where('tipe_voucher', 'pembelian')->where('checkout_id', $purchases->checkout_id)->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->get();
                
                $claim_ongkos_kirim_voucher = DB::table('claim_vouchers')->where('tipe_voucher', 'ongkos_kirim')->where('checkout_id', $purchases->checkout_id)->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->first();

                $purchases_address = DB::table('user_address')->where('user_id', $purchases->user_id)->first();

                $merchant_purchase = DB::table('product_purchases')->select('merchant_id')->where('purchase_id', $purchase_id)
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('merchant_id')->first();
    
                $cek_merchant_address = DB::table('merchant_address')->where('merchant_id', $merchant_purchase->merchant_id)->count();

                $merchant_address = DB::table('merchant_address')->where('merchant_id', $merchant_purchase->merchant_id)->first();


                $curl = curl_init();
                
                $param = $merchant_address->city_id;
                $subdistrict_id = $merchant_address->subdistrict_id;
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=".$param,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array("key: 41df939eff72c9b050a81d89b4be72ba"),
                ));

                $response = curl_exec($curl);
                $collection = json_decode($response, true);
                $filters =  array_filter($collection['rajaongkir']['results'], function($r) use ($subdistrict_id) {
                    return $r['subdistrict_id'] == $subdistrict_id;
                });
                
                foreach ($filters as $filter){
                    $lokasi_toko = $filter;
                }
                
                $err = curl_error($curl);
                curl_close($curl);
                
                $cek_user_address = DB::table('purchases')->where('purchases.user_id', $user_id)->where('purchase_id', $purchase_id)
                ->join('user_address', 'purchases.alamat_purchase', '=', 'user_address.user_address_id')->count();

                $user_address = DB::table('purchases')->where('purchases.user_id', $user_id)->where('purchase_id', $purchase_id)
                ->join('user_address', 'purchases.alamat_purchase', '=', 'user_address.user_address_id')->first();

                $curl_2 = curl_init();

                if($cek_user_address != 0){
                    $param = $user_address->city_id;
                    $subdistrict_id = $user_address->subdistrict_id;
                    
                    curl_setopt_array($curl_2, array(
                        CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=".$param,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array("key: 41df939eff72c9b050a81d89b4be72ba"),
                    ));

                    $response = curl_exec($curl_2);
                    $collection = json_decode($response, true);
                    $filters =  array_filter($collection['rajaongkir']['results'], function($r) use ($subdistrict_id) {
                        return $r['subdistrict_id'] == $subdistrict_id;
                    });
                    
                    foreach ($filters as $filter){
                        $lokasi_pembeli = $filter;
                    }
                    
                    $err = curl_error($curl_2);
                    curl_close($curl_2);                

                    $total_berat = DB::table('product_purchases')->select(DB::raw('SUM(heavy) as total_berat'))->where('user_id', $user_id)->where('product_purchases.purchase_id', $purchase_id)
                    ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                    ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->first();


                    $curl_3 = curl_init();
                    
                    $param = $merchant_address->city_id;
                    $merchant_subdistrict_id = $merchant_address->subdistrict_id;
                    $purchases_subdistrict_id = $user_address->subdistrict_id;
                    $courier_code = $purchases->courier_code;
                    $service = $purchases->service;

                    curl_setopt_array($curl_3, array(
                        CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => "origin=$purchases_subdistrict_id&originType=subdistrict&destination=$merchant_subdistrict_id&destinationType=subdistrict&weight=$total_berat->total_berat&courier=$purchases->courier_code",
                        CURLOPT_HTTPHEADER => array(
                            "content-type: application/x-www-form-urlencoded",
                            "key: 41df939eff72c9b050a81d89b4be72ba"
                        ),
                    ));
            
                    $response = curl_exec($curl_3);
                    $collection = json_decode($response, true);                

                    if($courier_code != "" || $service != ""){
                        // $filters =  array_filter($collection['rajaongkir']['results'], function($r) use ($courier_code) {
                        //     return $r['code'] == $courier_code;
                        // });
                        // foreach ($filters as $filter){
                        //     $courier_array = $filter;
                        // }
                        
                        // $filters2 =  array_filter($courier_array['costs'], function($s) use ($service){
                        //     return $s['service'] == $service;
                        // });
                        // foreach ($filters2 as $filter2){
                        //     $service_array = $filter2;
                        // }

                        // $filters3 =  array_filter($service_array['cost']);
                        // foreach ($filters3 as $filter3){
                        //     $ongkir = $filter3;
                        // }

                        if($courier_code == "pos"){ $courier_name = "POS Indonesia (POS)"; }
                        else if($courier_code == "jne"){ $courier_name = "POS Indonesia"; }

                        $service_name = $service;
                        
                        $ongkir = $purchases->ongkir;

                        // $courier_name = $courier_array["name"];
                        // $service_name = $service_array["description"];
                    }
                    
                    else{
                        $ongkir = 0;
                        $courier_name = "";
                        $service_name = "";
                    }

                    $err = curl_error($curl_3);
                    curl_close($curl_3);
                    
                }

                else if($cek_user_address == 0){
                    $lokasi_pembeli = "";
                    $ongkir = 0;
                    $courier_name = "";
                    $service_name = "";
                }
                
                

                // dd($ongkir["costs"]);
                // dd($courier_array["name"]);
                // dd($service_array["description"]);

                $product_purchases = DB::table('product_purchases')->where('user_id', $user_id)->where('product_purchases.purchase_id', $purchase_id)
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

                $product_specifications = DB::table('product_specifications')
                ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
                ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
                ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
                
                $total_harga_semula = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_semula'))
                ->where('product_purchases.purchase_id', $purchase_id)->where('user_id', $user_id)
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')->first();
                
                $total_harga = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga'))
                ->where('purchases.checkout_id', $purchases->checkout_id)
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();

                $cek_proof_of_payment = DB::table('proof_of_payments')->where('purchase_id', $purchase_id)->first();
        
                return view('user.pembelian.detail_pembelian')->with('claim_pembelian_vouchers', $claim_pembelian_vouchers)
                ->with('claim_ongkos_kirim_voucher', $claim_ongkos_kirim_voucher)->with('cek_merchant_address', $cek_merchant_address)
                ->with('merchant_address', $merchant_address)->with('lokasi_toko', $lokasi_toko)
                ->with('cek_user_address', $cek_user_address)->with('user_address', $user_address)->with('lokasi_pembeli', $lokasi_pembeli)
                ->with('product_purchases', $product_purchases)->with('profile', $profile)->with('product_specifications', $product_specifications)
                ->with('purchases', $purchases)->with('total_harga', $total_harga)->with('total_harga_semula', $total_harga_semula)
                ->with('cek_proof_of_payment', $cek_proof_of_payment)->with('ongkir', $ongkir)->with('courier_name', $courier_name)->with('service_name', $service_name);
                
            }
        }
    }

    public function PostBuktiPembayaran(Request $request, $purchase_id) {
        $proof_of_payment_image = $request -> file('proof_of_payment_image');

        $proof_of_payment_image_name = time().'_'.$proof_of_payment_image->getClientOriginalName();
        $tujuan_upload = './asset/u_file/proof_of_payment_image';
        $proof_of_payment_image->move($tujuan_upload,$proof_of_payment_image_name);

        DB::table('proof_of_payments')->insert([
            'purchase_id' => $purchase_id,
            'proof_of_payment_image' => $proof_of_payment_image_name,
        ]);
        
        
        return redirect()->back();
    }

    public function update_status_pembelian(Request $request, $purchase_id) {
        $purchases = DB::table('purchases')->where('purchase_id', $purchase_id)->first();

        if($purchases->status_pembelian == "status1"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status2',
            ]);
        }

        else if($purchases->status_pembelian == "status3"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status4',
            ]);
        }
        
        else if($purchases->status_pembelian == "status4"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status5',
            ]);
        }
        
        if($purchases->status_pembelian == "status1_ambil"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status2_ambil',
            ]);
        }
        
        else if($purchases->status_pembelian == "status2_ambil"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status3_ambil',
            ]);
        }
        
        else if($purchases->status_pembelian == "status3_ambil"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status4_ambil_a',
            ]);
        }
        
        else if($purchases->status_pembelian == "status4_ambil_a"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status4_ambil_b',
            ]);
        }
        
        else if($purchases->status_pembelian == "status4_ambil_b"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status5_ambil',
            ]);
        }

        return redirect()->back();
    }

    public function update_status2_pembelian(Request $request, $purchase_id) {
        $purchases = DB::table('purchases')->where('purchase_id', $purchase_id)->first();
        
        if($purchases->status_pembelian == "status2"){
            $no_resi = $request->no_resi;

            if($no_resi){
                DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                    'no_resi' => $no_resi,
                    'status_pembelian' => 'status3',
                ]);
            }
        }

        return redirect()->back();
    }
}
