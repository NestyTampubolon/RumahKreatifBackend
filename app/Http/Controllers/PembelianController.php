<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

use Carbon\Carbon;

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
        
        $total_berat = DB::table('carts')->select(DB::raw('SUM(heavy * jumlah_masuk_keranjang) as total_berat'))->where('user_id', $user_id)->where('merchant_id', $merchant_id)
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
        
        $target_metode_pembelian = $voucher_dipilih->target_metode_pembelian;

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

        // if($potongan > $voucher_dipilih->maksimal_pemotongan){
        //     $potongan = $voucher_dipilih->maksimal_pemotongan;
        // }

        $total_harga_fix = (int)$total_harga_checkout - $potongan;

        $total_harga_checkout = "Rp." . number_format($total_harga_fix,0,',','.');
        
        return response()->json(compact(['potongan', 'target_metode_pembelian']));
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
        
        $shipping_local = DB::table('shipping_locals')->where('merchant_id', $_GET['merchant_id'])
        ->where('shipping_local_subdistrict_id', $subdistrict_id)->first();

        return response()->json(compact(['filtered', 'shipping_local']));

        // return response()->json($filtered);
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
        date_default_timezone_set('Asia/Jakarta');

        $user_id = Auth::user()->id;
        
        $kode_pembelian = 'rkt_'.time();

        $merchant_id = $request -> merchant_id;
        
        $voucher_pembelian = $request -> voucher_pembelian;
        $voucher_ongkos_kirim = $request -> voucher_ongkos_kirim;
        
        $metode_pembelian = $request -> metode_pembelian;

        $harga_pembelian = $request -> harga_pembelian;
        $potongan_pembelian = $request -> potongan_pembelian;

        $alamat_purchase = $request -> alamat_purchase;
        
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
                'kode_pembelian' => $kode_pembelian,
                'user_id' => $user_id,
                'checkout_id' => $checkout_id->checkout_id,
                'alamat_purchase' => "",
                'harga_pembelian' => $harga_pembelian,
                'potongan_pembelian' => $potongan_pembelian,
                'status_pembelian' => "status1_ambil",
                'ongkir' => 0,
                'is_cancelled' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // if($metode_pembelian == "pemesanan_warehouse"){
        //     DB::table('purchases')->insert([
        //         'kode_pembelian' => $kode_pembelian,
        //         'user_id' => $user_id,
        //         'checkout_id' => $checkout_id->checkout_id,
        //         'alamat_purchase' => "",
        //         'harga_pembelian' => $harga_pembelian,
        //         'potongan_pembelian' => $potongan_pembelian,
        //         'status_pembelian' => "status1_ambil",
        //         'ongkir' => 0,
        //         'is_cancelled' => 0,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]);
        // }
        
        if($metode_pembelian == "pesanan_dikirim"){
            $ongkir = $request -> ongkir;
            DB::table('purchases')->insert([
                'kode_pembelian' => $kode_pembelian,
                'user_id' => $user_id,
                'checkout_id' => $checkout_id->checkout_id,
                'alamat_purchase' => $alamat_purchase,
                'harga_pembelian' => $harga_pembelian,
                'potongan_pembelian' => $potongan_pembelian,
                'status_pembelian' => "status1",
                'courier_code' => $courier_code,
                'service' => $service,
                'ongkir' => $ongkir,
                'is_cancelled' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        
        
        $purchase_id = DB::table('purchases')->select('purchase_id')->orderBy('purchase_id', 'desc')->first();

        $product_purchase = DB::table('carts')->select('carts.product_id', 'heavy', 'jumlah_masuk_keranjang', 'price')->where('user_id', $user_id)
        ->where('merchant_id', $merchant_id)->join('products', 'carts.product_id', '=', 'products.product_id')->get();

        foreach($product_purchase as $product_purchase){
            DB::table('product_purchases')->insert([
                'purchase_id' => $purchase_id->purchase_id,
                'product_id' => $product_purchase->product_id,
                'berat_pembelian_produk' => $product_purchase->jumlah_masuk_keranjang * $product_purchase->heavy,
                'jumlah_pembelian_produk' => $product_purchase->jumlah_masuk_keranjang,
                'harga_pembelian_produk' => $product_purchase->jumlah_masuk_keranjang * $product_purchase->price,
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

            $purchases = DB::table('product_purchases')->select('product_purchases.purchase_id', 'kode_pembelian', 'status_pembelian', 'name', 'username')->where('merchant_id', $toko)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')->join('products', 'product_purchases.product_id', '=', 'products.product_id')
            ->join('profiles', 'purchases.user_id', '=', 'profiles.user_id')->join('users', 'purchases.user_id', '=', 'users.id')
            ->orderBy('product_purchases.purchase_id', 'desc')->groupBy('purchase_id', 'kode_pembelian', 'status_pembelian', 'name', 'username')->get();

            $jumlah_purchases = DB::table('product_purchases')->where('merchant_id', $toko)
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchase_id')->count();
            
            $product_purchases = DB::table('product_purchases')->where('merchant_id', $toko)->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            $product_specifications = DB::table('product_specifications')
            ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
            ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
            ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
            
            $count_proof_of_payment = DB::table('proof_of_payments')->select(DB::raw('COUNT(*) as count_proof_of_payment'))->first();

            $proof_of_payments = DB::table('proof_of_payments')->get();

            return view('user.toko.daftar_pembelian')->with('purchases', $purchases)->with('jumlah_purchases', $jumlah_purchases)
            ->with('product_purchases', $product_purchases)->with('product_specifications', $product_specifications)
            ->with('proof_of_payments', $proof_of_payments)->with('count_proof_of_payment', $count_proof_of_payment);
        }

        else{
            $user_id = Auth::user()->id;

            $cek_admin_id = DB::table('users')->where('id', $user_id)->where('is_admin', 1)->first();

            if($cek_admin_id){
                $data = DB::table("purchases as p")
                    ->join("profiles", "profiles.user_id", "=", "p.user_id")
                    ->joinSub(DB::table("product_purchases as pp")
                        ->join("products as p", "pp.product_id", "p.product_id")
                        ->join("merchants as m", "m.merchant_id", "p.merchant_id")
                        ->select("pp.purchase_id", "m.nama_merchant"), "mp", function($join){
                            $join->on("p.purchase_id", "=", "mp.purchase_id");
                        })
                    ->leftJoin("proof_of_payments as ppp", "ppp.purchase_id", "=", "p.purchase_id")
                    ->select("p.purchase_id", "profiles.name", "p.kode_pembelian", "mp.nama_merchant", "p.created_at", "p.updated_at", "p.status_pembelian","ppp.proof_of_payment_image")
                    ->where('p.is_cancelled', 0)
                    ->groupBy("p.purchase_id", "profiles.name", "p.kode_pembelian", "mp.nama_merchant", "p.created_at", "p.updated_at", "p.status_pembelian", "ppp.proof_of_payment_image")
                    ->get();

                return view('admin.daftar_pembelian', [
                    "purchases"=> $data,
                ]);
            }

            else{
                $checkouts = DB::table('checkouts')->where('user_id', $user_id)
                ->join('users', 'checkouts.user_id', '=', 'users.id')->orderBy('checkout_id', 'desc')->get();
                
                $claim_vouchers = DB::table('claim_vouchers')->where('tipe_voucher', 'pembelian')->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->get();

                $cek_purchases = DB::table('purchases')->where('user_id', $user_id)->first();

                $purchases = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)
                ->join('users', 'purchases.user_id', '=', 'users.id')->orderBy('purchase_id', 'desc')->get();
                
                $cancelled_purchases = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 1)
                ->join('users', 'purchases.user_id', '=', 'users.id')->orderBy('purchase_id', 'desc')->get();
                
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
                ->with('purchases', $purchases)->with('cancelled_purchases', $cancelled_purchases)->with('cek_purchases', $cek_purchases)->with('product_purchases', $product_purchases)->with('profile', $profile)
                ->with('product_specifications', $product_specifications)->with('count_proof_of_payment', $count_proof_of_payment);
            }
        }
    }

    public function detail_purchase(Request $request, $id){
        $purchase = null;
        $purchase = DB::table("purchases")->where("purchase_id", $id)
            ->first();
        
        $products = null;
        $products = DB::table("product_purchases as pp")
            ->join("products as p", "pp.product_id", "=", "p.product_id")
            ->where("purchase_id", $id)
            ->get();
        
        $claim_pembelian_voucher = null;
        $claim_pembelian_voucher = DB::table('claim_vouchers')
            ->where('tipe_voucher', 'pembelian')->where('purchase_id', $id)
            ->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')
            ->join('purchases', 'claim_vouchers.checkout_id', '=', 'purchases.checkout_id')
            ->first();
            
        $target_kategori = null;

        $subtotal_harga_produk = null;
        $potongan_subtotal = null;
        $subtotal_harga_produk_terkait = null;
        $subtotal_harga_produk_terkait_seluruh = null;
        $jumlah_potongan_subtotal = null;

        if($claim_pembelian_voucher){
            $target_kategori = explode(",", $claim_pembelian_voucher->target_kategori);

            foreach($target_kategori as $target_kategori_subtotal){
                $subtotal_harga_produk = DB::table('product_purchases')
                ->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
                ->where('purchases.purchase_id', $id)->where('category_id', $target_kategori_subtotal)
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
                ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
                ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')->first();

                $potongan_subtotal[] = (int)$subtotal_harga_produk->total_harga_pembelian * $claim_pembelian_voucher->potongan / 100;

                $subtotal_harga_produk_terkait[] = (int)$subtotal_harga_produk->total_harga_pembelian;
            }

            $subtotal_harga_produk_terkait_seluruh = array_sum($subtotal_harga_produk_terkait);
            

            if($purchase->potongan_pembelian != null){
                $jumlah_potongan_subtotal = $purchase->potongan_pembelian;
            }
            
            else if($purchase->potongan_pembelian == null){
                $jumlah_potongan_subtotal = array_sum($potongan_subtotal);
            }
        }
        
        $claim_ongkos_kirim_voucher = null;
        $claim_ongkos_kirim_voucher = DB::table('claim_vouchers')->where('tipe_voucher', 'ongkos_kirim')->where('purchase_id', $id)
            ->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')
            ->join('purchases', 'claim_vouchers.checkout_id', '=', 'purchases.checkout_id')
            ->first();

        $total_harga_pembelian = null;
        $total_harga_pembelian = DB::table('product_purchases')->select(DB::raw('SUM(price * jumlah_pembelian_produk) as total_harga_pembelian'))
            ->where('purchases.purchase_id', $id)
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('checkouts', 'purchases.checkout_id', '=', 'checkouts.checkout_id')
            ->first();
        
        $semua_total_harga_pembelian = null;
        if($purchase->harga_pembelian == null){
            $semua_total_harga_pembelian = $total_harga_pembelian->total_harga_pembelian;
        }
  
        else if($purchase->harga_pembelian != null){
            $semua_total_harga_pembelian = $purchase->harga_pembelian;
        }
        
        $courier_name = null;
        if($purchase->courier_code = "pos"){ $courier_name = "POS Indonesia (POS)"; }
        else if($purchase->courier_code = "jne"){ $courier_name = "Jalur Nugraha Eka (JNE)"; }

        $proof_of_payment = null;
        $proof_of_payment = DB::table('proof_of_payments')->where('purchase_id', $id)
        ->first();

        $ongkir = null;
        $ongkir = $purchase->ongkir;
        $ongkir_get_voucher = null;

        if($claim_ongkos_kirim_voucher){
            $ongkir_get_voucher = $purchase->ongkir - $claim_ongkos_kirim_voucher->potongan;

            if($ongkir_get_voucher < 0 ){
                $ongkir_get_voucher = 0;
            }
        }

        return response()->json([
            "purchase" => $purchase,
            "products"=> $products,
            "claim_pembelian_voucher" => $claim_pembelian_voucher,
            "target_kategori" => $target_kategori,
            "subtotal_harga_produk_terkait_seluruh" => $subtotal_harga_produk_terkait_seluruh,
            "jumlah_potongan_subtotal" => $jumlah_potongan_subtotal,
            "claim_ongkos_kirim_voucher" => $claim_ongkos_kirim_voucher,
            "semua_total_harga_pembelian" => $semua_total_harga_pembelian,
            "proof_of_payment" => $proof_of_payment,
            "courier_name" => $courier_name,
            "ongkir" => $ongkir,
            "ongkir_get_voucher" => $ongkir_get_voucher,
        ], 200);
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
            ->with('purchases', $purchases)->with('cek_proof_of_payment', $cek_proof_of_payment)->with('profile', $profile)->with('total_harga', $total_harga)
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
                
                $claim_pembelian_voucher = DB::table('claim_vouchers')->where('tipe_voucher', 'pembelian')->where('checkout_id', $purchases->checkout_id)->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->first();
                
                $claim_ongkos_kirim_voucher = DB::table('claim_vouchers')->where('tipe_voucher', 'ongkos_kirim')->where('checkout_id', $purchases->checkout_id)->join('vouchers', 'claim_vouchers.voucher_id', '=', 'vouchers.voucher_id')->first();

                $purchases_address = DB::table('user_address')->where('user_id', $purchases->user_id)->first();

                $merchant_purchase = DB::table('product_purchases')->select('merchant_id')->where('purchase_id', $purchase_id)
                ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('merchant_id')->first();
    
                $merchant_info = DB::table('merchants')->where('merchant_id', $merchant_purchase->merchant_id)->first();

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
        
                return view('user.pembelian.detail_pembelian')->with('claim_pembelian_voucher', $claim_pembelian_voucher)
                ->with('claim_ongkos_kirim_voucher', $claim_ongkos_kirim_voucher)->with('merchant_info', $merchant_info)
                ->with('cek_merchant_address', $cek_merchant_address)->with('merchant_address', $merchant_address)->with('lokasi_toko', $lokasi_toko)
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

    public function batalkan_pembelian(Request $request, $purchase_id) {

        DB::table('purchases')->where('purchase_id', $purchase_id)->update([
            'is_cancelled' => 1,
        ]);

        return redirect()->back();
    }

    public function update_status_pembelian(Request $request, $purchase_id) {
        date_default_timezone_set('Asia/Jakarta');

        
        $purchases = DB::table('purchases')->where('purchase_id', $purchase_id)->first();

        if($purchases->status_pembelian == "status1"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status2',
                'updated_at' => Carbon::now(),
            ]);
        }

        else if($purchases->status_pembelian == "status3"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status4',
                'updated_at' => Carbon::now(),
            ]);
        }
        
        else if($purchases->status_pembelian == "status4"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status5',
                'updated_at' => Carbon::now(),
            ]);
        }
        
        if($purchases->status_pembelian == "status1_ambil"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status2_ambil',
                'updated_at' => Carbon::now(),
            ]);
        }
        
        else if($purchases->status_pembelian == "status2_ambil"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status3_ambil',
                'updated_at' => Carbon::now(),
            ]);
        }
        
        else if($purchases->status_pembelian == "status3_ambil"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status4_ambil_a',
                'updated_at' => Carbon::now(),
            ]);
        }
        
        else if($purchases->status_pembelian == "status4_ambil_a"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status4_ambil_b',
                'updated_at' => Carbon::now(),
            ]);
        }
        
        else if($purchases->status_pembelian == "status4_ambil_b"){
            DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                'status_pembelian' => 'status5_ambil',
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->back();
    }

    public function update_status2_pembelian(Request $request, $purchase_id) {
        date_default_timezone_set('Asia/Jakarta');

        $purchases = DB::table('purchases')->where('purchase_id', $purchase_id)->first();
        
        if($purchases->status_pembelian == "status2"){
            $no_resi = $request->no_resi;

            if($no_resi){
                DB::table('purchases')->where('purchase_id', $purchase_id)->update([
                    'no_resi' => $no_resi,
                    'status_pembelian' => 'status3',
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        return redirect()->back();
    }

    public function update_no_resi(Request $request, $purchase_id) {
        date_default_timezone_set('Asia/Jakarta');

        $no_resi = $request->no_resi;
        
        DB::table('purchases')->where('purchase_id', $purchase_id)->update([
            'no_resi' => $no_resi,
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->back();
    }


    
}
