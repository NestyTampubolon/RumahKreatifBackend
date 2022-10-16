<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class KeranjangController extends Controller
{
    public function keranjang() {
        $user_id = Auth::user()->id;
        // $products = DB::table('products')->orderBy('product_id', 'desc')->join('categories', 'products.category_id', '=', 'categories.category_id')
        // ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->get();

        $merchants = DB::table('merchants')->get();
        
        // $merchant_purchase = DB::table('product_purchases')->select('merchant_id')->where('purchase_id', $purchase_id)
        //         ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->groupBy('merchant_id')->first();

        $cart_by_merchants = DB::table('carts')->select('merchant_id')->where('user_id', $user_id)
        ->join('products', 'carts.product_id', '=', 'products.product_id')->groupBy('merchant_id')->get();

        $carts = DB::table('carts')->where('user_id', $user_id)->join('products', 'carts.product_id', '=', 'products.product_id')->get();
       
        $cek_carts = DB::table('carts')->where('user_id', $user_id)->first();
        
        $stocks = DB::table('stocks')->get();

        return view('user.pembelian.keranjang')->with('merchants', $merchants)->with('cart_by_merchants', $cart_by_merchants)
        ->with('carts', $carts)->with('cek_carts', $cek_carts)->with('stocks', $stocks);
    }

    // public function masuk_keranjang(Request $request, $product_id) {
    //     $user_id = Auth::user()->id;
        
    //     $jumlah_masuk_keranjang = DB::table('carts')->select('jumlah_masuk_keranjang')->where('user_id', $user_id)->where('product_id', $product_id)->first();

    //     $cek_keranjang = DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_id)->first();
        
    //     if($cek_keranjang){
    //         DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_id)->update([
    //             'jumlah_masuk_keranjang' => $jumlah_masuk_keranjang->jumlah_masuk_keranjang + 1,
    //         ]);
    //     }
        
    //     else{
    //         DB::table('carts')->insert([
    //             'user_id' => $user_id,
    //             'product_id' => $product_id,
    //             'jumlah_masuk_keranjang' => 1,
    //         ]);
    //     }

    //     return redirect()->back();
    // }
    
    public function masuk_keranjang(Request $request) {
        $user_id = Auth::user()->id;
        $produk = $_GET['produk'];
        
        $jumlah_masuk_keranjang = DB::table('carts')->select('jumlah_masuk_keranjang')->where('user_id', $user_id)->where('product_id', $produk)->first();

        $cek_keranjang = DB::table('carts')->where('user_id', $user_id)->where('product_id', $produk)->first();
        
        if($cek_keranjang){
            DB::table('carts')->where('user_id', $user_id)->where('product_id', $produk)->update([
                'jumlah_masuk_keranjang' => $jumlah_masuk_keranjang->jumlah_masuk_keranjang + 1,
            ]);
        }
        
        else{
            DB::table('carts')->insert([
                'user_id' => $user_id,
                'product_id' => $produk,
                'jumlah_masuk_keranjang' => 1,
            ]);
        }

        $jumlah_produk_keranjang = DB::table('carts')->where('user_id', $user_id)->count();

        return response()->json($jumlah_produk_keranjang);
    }

    public function masuk_keranjang_beli(Request $request, $product_id) {
        $user_id = Auth::user()->id;
        
        $jumlah_pembelian_produk = $request -> jumlah_pembelian_produk;

        $cek_keranjang = DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_id)->first();
        
        $jumlah_masuk_keranjang = DB::table('carts')->select('jumlah_masuk_keranjang')->where('user_id', $user_id)->where('product_id', $product_id)->first();

        if($cek_keranjang){
            DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_id)->update([
                'jumlah_masuk_keranjang' => $jumlah_masuk_keranjang->jumlah_masuk_keranjang + $jumlah_pembelian_produk,
            ]);
        }
        
        else{
            DB::table('carts')->insert([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'jumlah_masuk_keranjang' => $jumlah_pembelian_produk,
            ]);
        }

        return redirect('keranjang');
    }

    public function HapusKeranjang($cart_id)
    {
        DB::table('carts')->where('cart_id', $cart_id)->delete();
        
        return redirect('./keranjang');
    }
}
