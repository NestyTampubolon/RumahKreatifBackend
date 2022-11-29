<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class HomeController extends Controller
{
    public function index() {
        if(Auth::check()){
            $id = Auth::user()->id;
            $cek_admin_id = DB::table('users')->where('id', $id)->where('is_admin', 1)->first();
        }
        
        if(isset($cek_admin_id)){
            return view('admin.index');
        }
 
        if(Session::get('toko')){
            return redirect('./toko');
        }

        else{
            
            $total_penjualan_produk = DB::table('product_purchases')->select(DB::raw('SUM(jumlah_pembelian_produk) as count_products'),
            'product_purchases.product_id')->where('is_deleted', 0)
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->groupBy('product_purchases.product_id')->orderBy('count_products', 'desc')->limit(10)->get();

            $carousels = DB::table('carousels')->orderBy('id', 'desc')->get();
            
            $count_products = DB::table('products')->select(DB::raw('COUNT(*) as count_products'))->first();

            $products = DB::table('products')->where('is_deleted', 0)->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->orderBy('product_id', 'desc')->limit(10)->get();

            $cek_http = DB::table('carousels')->where('link_carousel', 'like', 'https://'."%")->orwhere('link_carousel', 'like', 'http://'."%")->first();
            $cek_www = DB::table('carousels')->where('link_carousel', 'like', 'www.'."%")->first();

            return view('user.index')->with('products', $products)->with('total_penjualan_produk', $total_penjualan_produk)->with('carousels', $carousels)->with('cek_http', $cek_http)
            ->with('cek_www', $cek_www)->with('count_products', $count_products);
        }
    }

    public function dashboard() {
        if(Session::get('toko')){
            $toko = Session::get('toko');

            $cek_purchases = DB::table('product_purchases')->select('product_purchases.purchase_id')->where('merchant_id', $toko)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.purchase_id', 'desc')->groupBy('purchase_id')
            ->orderBy('purchases.updated_at', 'desc')->get();

            $purchases = DB::table('purchases')->join('users', 'purchases.user_id', '=', 'users.id')->where('is_cancelled', 0)->orderBy('purchases.updated_at', 'desc')->get();
            
            $count_status2 = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status2')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status2 = 0;
            foreach($count_status2 as $count_status2){
                $count_purchases_status2[] = DB::table('purchases')->where('purchase_id', $count_status2->purchase_id)->count();
                $jumlah_status2 = array_sum($count_purchases_status2);
            }
            $count_status2_ambil = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status2_ambil')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status2_ambil = 0;
            foreach($count_status2_ambil as $count_status2_ambil){
                $count_purchases_status2_ambil[] = DB::table('purchases')->where('purchase_id', $count_status2_ambil->purchase_id)->count();
                $jumlah_status2_ambil = array_sum($count_purchases_status2_ambil);
            }
            $count_status3 = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status3')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status3 = 0;
            foreach($count_status3 as $count_status3){
                $count_purchases_status3[] = DB::table('purchases')->where('purchase_id', $count_status3->purchase_id)->count();
                $jumlah_status3 = array_sum($count_purchases_status3);
            }
            $count_status3_ambil = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status3_ambil')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status3_ambil = 0;
            foreach($count_status3_ambil as $count_status3_ambil){
                $count_purchases_status3_ambil[] = DB::table('purchases')->where('purchase_id', $count_status3_ambil->purchase_id)->count();
                $jumlah_status3_ambil = array_sum($count_purchases_status3_ambil);
            }
            $count_status4_ambil_a = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status4_ambil_a')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status4_ambil_a = 0;
            foreach($count_status4_ambil_a as $count_status4_ambil_a){
                $count_purchases_status4_ambil_a[] = DB::table('purchases')->where('purchase_id', $count_status4_ambil_a->purchase_id)->count();
                $jumlah_status4_ambil_a = array_sum($count_purchases_status4_ambil_a);
            }
            $jumlah_pesanan_sedang_berlangsung = $jumlah_status2 + $jumlah_status2_ambil + $jumlah_status3
            + $jumlah_status3_ambil + $jumlah_status4_ambil_a;

            $count_status4 = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status4')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status4 = 0;
            foreach($count_status4 as $count_status4){
                $count_purchases_status4[] = DB::table('purchases')->where('purchase_id', $count_status4->purchase_id)->count();
                $jumlah_status4 = array_sum($count_purchases_status4);
            }
            $count_status4_ambil_b = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status4_ambil_b')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status4_ambil_b = 0;
            foreach($count_status4_ambil_b as $count_status4_ambil_b){
                $count_purchases_status4_ambil_b[] = DB::table('purchases')->where('purchase_id', $count_status4_ambil_b->purchase_id)->count();
                $jumlah_status4_ambil_b = array_sum($count_purchases_status4_ambil_b);
            }
            $jumlah_pesanan_berhasil_belum_dibayar = $jumlah_status4 + $jumlah_status4_ambil_b;
            
            $count_status5 = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status5')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status5 = 0;
            foreach($count_status5 as $count_status5){
                $count_purchases_status5[] = DB::table('purchases')->where('purchase_id', $count_status5->purchase_id)->count();
                $jumlah_status5 = array_sum($count_purchases_status5);
            }
            $count_status5_ambil = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->where('is_cancelled', 0)->where('status_pembelian', 'status5_ambil')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            $jumlah_status5_ambil = 0;
            foreach($count_status5_ambil as $count_status5_ambil){
                $count_purchases_status5_ambil[] = DB::table('purchases')->where('purchase_id', $count_status5_ambil->purchase_id)->count();
                $jumlah_status5_ambil = array_sum($count_purchases_status5_ambil);
            }
            $jumlah_pesanan_berhasil_telah_dibayar = $jumlah_status5 + $jumlah_status5_ambil;
            
            return view('user.toko.dashboard')->with('toko', $toko)->with('cek_purchases', $cek_purchases)->with('purchases', $purchases)
            ->with('jumlah_pesanan_sedang_berlangsung', $jumlah_pesanan_sedang_berlangsung)->with('jumlah_pesanan_berhasil_belum_dibayar', $jumlah_pesanan_berhasil_belum_dibayar)
            ->with('jumlah_pesanan_berhasil_telah_dibayar', $jumlah_pesanan_berhasil_telah_dibayar);
        }
        
        
        if(Auth::user()){
            $user_id = Auth::user()->id;

            $cek_purchases = DB::table('purchases')->where('user_id', $user_id)->first();

            $purchases = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)
            ->join('users', 'purchases.user_id', '=', 'users.id')->orderBy('purchases.updated_at', 'desc')->get();
            
            $count_status1 = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status1')->count();
            $count_status1_ambil = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status1_ambil')->count();
            $count_status2 = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status2')->count();
            $count_status2_ambil = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status2_ambil')->count();
            $count_status3 = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status3')->count();
            $count_status3_ambil = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status3_ambil')->count();
            $count_status4_ambil_a = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status4_ambil_a')->count();
            $jumlah_pesanan_sedang_berlangsung = $count_status1 + $count_status1_ambil + $count_status2 + $count_status2_ambil + $count_status3 + $count_status3_ambil + $count_status4_ambil_a;

            $count_status4 = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status4')->count();
            $count_status4_ambil_b = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status4_ambil_b')->count();
            $count_status5 = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status5')->count();
            $count_status5_ambil = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 0)->where('status_pembelian', 'status5_ambil')->count();
            $jumlah_pesanan_berhasil = $count_status4 + $count_status4_ambil_b + $count_status5 + $count_status5_ambil;
            
            $count_proof_of_payment = DB::table('proof_of_payments')->select(DB::raw('COUNT(*) as count_proof_of_payment'))->first();
            
            $count_cancelled_purchases = DB::table('purchases')->where('user_id', $user_id)->where('is_cancelled', 1)->count();

            return view('user.dashboard')->with('cek_purchases', $cek_purchases)->with('purchases', $purchases)
            ->with('count_proof_of_payment', $count_proof_of_payment)->with('jumlah_pesanan_sedang_berlangsung', $jumlah_pesanan_sedang_berlangsung)
            ->with('jumlah_pesanan_berhasil', $jumlah_pesanan_berhasil)->with('count_cancelled_purchases', $count_cancelled_purchases);
        }
        
        else{
            return redirect('./');
        }
    }
}
