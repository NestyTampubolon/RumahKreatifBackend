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

        $carts = DB::table('carts')->where('user_id', $user_id)->join('products', 'carts.product_id', '=', 'products.product_id')->get();

        return view('user.pembelian.keranjang')->with('carts', $carts);
    }

    public function masuk_keranjang(Request $request, $product_id) {
        $user_id = Auth::user()->id;

        $cek_keranjang = DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_id)->first();
        
        $jumlah_masuk_keranjang = DB::table('carts')->select('jumlah_masuk_keranjang')->where('user_id', $user_id)->where('product_id', $product_id)->first();

        if($cek_keranjang){
            DB::table('carts')->where('user_id', $user_id)->where('product_id', $product_id)->update([
                'jumlah_masuk_keranjang' => $jumlah_masuk_keranjang->jumlah_masuk_keranjang + 1,
            ]);
        }
        
        else{
            DB::table('carts')->insert([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'jumlah_masuk_keranjang' => 1,
            ]);
        }

        return redirect()->back();
    }

    public function HapusKeranjang($cart_id)
    {
        DB::table('carts')->where('cart_id', $cart_id)->delete();
        
        return redirect('./keranjang');
    }
}
