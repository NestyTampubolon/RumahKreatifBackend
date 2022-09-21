<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class PembelianController extends Controller
{
    public function PostBeliProduk(Request $request, $product_id) {
        $user_id = Auth::user()->id;

        $jumlah_pembelian_produk = $request -> jumlah_pembelian_produk;

        DB::table('purchases')->insert([
            'user_id' => $user_id,
            'status_pembelian' => "status1",
        ]);
        
        $purchase_id = DB::table('purchases')->select('purchase_id')->orderBy('purchase_id', 'desc')->first();

        DB::table('product_purchases')->insert([
            'purchase_id' => $purchase_id->purchase_id,
            'product_id' => $product_id,
            'jumlah_pembelian_produk' => $jumlah_pembelian_produk,
        ]);

        return redirect('../daftar_pembelian');
    }

    public function daftar_pembelian() {

        if(Session::get('toko')){
            $toko = Session::get('toko');
            $product_purchases = DB::table('product_purchases')->where('merchant_id', $toko)->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            $product_specifications = DB::table('product_specifications')
            ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
            ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
            ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();

            return view('user.toko.daftar_pembelian')->with('product_purchases', $product_purchases)->with('product_specifications', $product_specifications);
        }

        else{
            $user_id = Auth::user()->id;
            $profile = DB::table('profiles')->where('user_id', $user_id)->first();
            
            $product_purchases = DB::table('product_purchases')->where('user_id', $user_id)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            $product_specifications = DB::table('product_specifications')
            ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
            ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
            ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
    
            return view('user.pembelian.daftar_pembelian')->with('product_purchases', $product_purchases)->with('profile', $profile)
            ->with('product_specifications', $product_specifications);
        }
    }

    public function detail_pembelian($product_purchase_id) {
        if(Session::get('toko')){
            $toko = Session::get('toko');
            $product_purchases = DB::table('product_purchases')->where('merchant_id', $toko)->where('product_purchase_id', $product_purchase_id)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            $product_specifications = DB::table('product_specifications')
            ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
            ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
            ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();

            return view('user.toko.detail_pembelian')->with('product_purchases', $product_purchases)->with('product_specifications', $product_specifications);
        }

        else{
            $user_id = Auth::user()->id;
            $profile = DB::table('profiles')->where('user_id', $user_id)->first();

            $product_purchases = DB::table('product_purchases')->where('user_id', $user_id)->where('product_purchase_id', $product_purchase_id)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            $product_specifications = DB::table('product_specifications')
            ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
            ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
            ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->get();
    
            return view('user.pembelian.detail_pembelian')->with('product_purchases', $product_purchases)->with('profile', $profile)
            ->with('product_specifications', $product_specifications);
        }
    }
}
