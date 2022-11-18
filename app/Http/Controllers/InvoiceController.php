<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;
use PDF;

class InvoiceController extends Controller
{
    public function invoice_pembelian($purchase_id)
    {
        $user_id = Auth::user()->id;
        
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
                if($courier_code == "pos"){ $courier_name = "POS Indonesia (POS)"; }
                else if($courier_code == "jne"){ $courier_name = "Jalur Nugraha Eka (JNE)"; }

                $service_name = $service;
                
                $ongkir = $purchases->ongkir;
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

        return view('user.pembelian.invoice_pembelian', compact(['claim_pembelian_vouchers', 'claim_ongkos_kirim_voucher', 'product_purchases', 'purchases', 'product_specifications', 'profile', 'ongkir', 'courier_name', 'service_name']));

        $pdf = PDF::loadview('user.pembelian.invoice_pembelian', compact(['claim_pembelian_vouchers', 'claim_ongkos_kirim_voucher', 'product_purchases', 'purchases', 'product_specifications', 'profile', 'ongkir', 'courier_name', 'service_name']));

    	return $pdf->download("invoice_pembelian_$purchases->kode_pembelian.pdf");
    }


    public function invoice_penjualan($purchase_id)
    {
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
                if($courier_code == "pos"){ $courier_name = "POS Indonesia (POS)"; }
                else if($courier_code == "jne"){ $courier_name = "Jalur Nugraha Eka (JNE)"; }

                $service_name = $service;
                
                $ongkir = $purchases->ongkir;
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

        return view('user.toko.invoice_penjualan', compact(['product_purchases', 'purchases', 'product_specifications', 'total_harga', 'profile', 'ongkir', 'courier_name', 'service_name']));

        $pdf = PDF::loadview('user.toko.invoice_penjualan', compact(['product_purchases', 'purchases', 'product_specifications', 'total_harga', 'profile', 'ongkir', 'courier_name', 'service_name']));

    	return $pdf->download("invoice_penjualan_$purchases->kode_pembelian.pdf");
    }
}
