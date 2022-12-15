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
                ->where('p.is_cancelled', 0)->where("p.status_pembelian", "status1")->orwhere("p.status_pembelian", "status1_ambil")->where("ppp.proof_of_payment_image", "!=", null)
                ->groupBy("p.purchase_id", "profiles.name", "p.kode_pembelian", "mp.nama_merchant", "p.created_at", "p.updated_at", "p.status_pembelian", "ppp.proof_of_payment_image")
                ->get();
            
            $jumlah_pesanan = DB::table('purchases')->where('is_cancelled', 0)->count();
            $jumlah_pesanan_perlu_konfirmasi = DB::table('purchases')
            ->leftJoin("proof_of_payments", "proof_of_payments.purchase_id", "=", "purchases.purchase_id")
            ->where('is_cancelled', 0)->where("status_pembelian", "status1")
            ->orwhere("status_pembelian", "status1_ambil")->where("proof_of_payment_image", "!=", null)->count();

            $jumlah_pengguna = DB::table('profiles')->count();
            $jumlah_pengguna_perlu_verifikasi = DB::table('profiles')
            ->leftJoin("verify_users", "verify_users.user_id", "=", "profiles.user_id")
            ->where('is_verified', "!=", 1)->groupBy("profiles.user_id",)
            ->count();

            $toko = DB::table('merchants')->count();
            $toko_perlu_verifikasi = DB::table('merchants')
            ->where('is_verified', "!=", 1)
            ->count();

            return view('admin.index', [
                "purchases"=> $data,
                "jumlah_pesanan"=> $jumlah_pesanan,
                "jumlah_pesanan_perlu_konfirmasi"=> $jumlah_pesanan_perlu_konfirmasi,
                "jumlah_pengguna"=> $jumlah_pengguna,
                "jumlah_pengguna_perlu_verifikasi"=> $jumlah_pengguna_perlu_verifikasi,
                "jumlah_toko"=> $toko,
                "jumlah_toko_perlu_verifikasi"=> $toko_perlu_verifikasi,

            ]);

            // return view('admin.index');
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
            
            $count_status = DB::table('product_purchases')->select('purchases.purchase_id')->where('merchant_id', $toko)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('purchases.purchase_id')->get();
            
            $jumlah_status2 = 0;
            $jumlah_status2_ambil = 0;
            $jumlah_status3 = 0;
            $jumlah_status3_ambil = 0;
            $jumlah_status4_ambil_a = 0;
            $jumlah_status4 = 0;
            $jumlah_status4_ambil_b = 0;
            $jumlah_status5 = 0;
            $jumlah_status5_ambil = 0;
            foreach($count_status as $count_status){
                $count_purchases_status2[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status2')->count();
                $jumlah_status2 = array_sum($count_purchases_status2);
                
                $count_purchases_status2_ambil[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status2_ambil')->count();
                $jumlah_status2_ambil = array_sum($count_purchases_status2_ambil);
                
                $count_purchases_status3[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status3')->count();
                $jumlah_status3 = array_sum($count_purchases_status3);
                
                $count_purchases_status3_ambil[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status3_ambil')->count();
                $jumlah_status3_ambil = array_sum($count_purchases_status3_ambil);
                
                $count_purchases_status4_ambil_a[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status4_ambil_a')->count();
                $jumlah_status4_ambil_a = array_sum($count_purchases_status4_ambil_a);
                
                $count_purchases_status4[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status4')->count();
                $jumlah_status4 = array_sum($count_purchases_status4);
                
                $count_purchases_status4_ambil_b[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status4_ambil_b')->count();
                $jumlah_status4_ambil_b = array_sum($count_purchases_status4_ambil_b);
                
                $count_purchases_status5[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status5')->count();
                $jumlah_status5 = array_sum($count_purchases_status5);
                
                $count_purchases_status5_ambil[] = DB::table('purchases')->where('purchase_id', $count_status->purchase_id)->where('status_pembelian', 'status5_ambil')->count();
                $jumlah_status5_ambil = array_sum($count_purchases_status5_ambil);
            }
            $jumlah_pesanan_sedang_berlangsung = $jumlah_status2 + $jumlah_status2_ambil + $jumlah_status3
            + $jumlah_status3_ambil + $jumlah_status4_ambil_a;

            $jumlah_pesanan_berhasil_belum_dibayar = $jumlah_status4 + $jumlah_status4_ambil_b;
            
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
