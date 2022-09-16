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
        
        $specification_id = $request -> specification_id;

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
        
        $product_purchase_id = DB::table('product_purchases')->select('product_purchase_id')->orderBy('product_purchase_id', 'desc')->first();
        
        $jumlah_specification_id_dipilih = count($specification_id);
        
        for($x=0;$x<$jumlah_specification_id_dipilih;$x++){
            DB::table('purchase_product_specifications')->insert([
                'product_purchase_id' => $product_purchase_id->product_purchase_id,
                'specification_id' => $specification_id[$x],
            ]);
        }

        return redirect('../daftar_pembelian');
    }

    public function daftar_pembelian() {        
        $purchase_product_specifications = DB::table('purchase_product_specifications')
        ->join('product_purchases', 'purchase_product_specifications.product_purchase_id', '=', 'product_purchases.product_purchase_id')
        ->join('specifications', 'purchase_product_specifications.specification_id', '=', 'specifications.specification_id')
        ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')
        ->orderBy('purchase_product_specification_id', 'desc')->get();

        if(Session::get('toko')){
            $product_purchases = DB::table('product_purchases')->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            return view('user.toko.daftar_pembelian')->with('product_purchases', $product_purchases)
            ->with('purchase_product_specifications', $purchase_product_specifications);
        }

        else{
            $user_id = Auth::user()->id;
            $profile = DB::table('profiles')->where('user_id', $user_id)->first();
            
            $product_purchases = DB::table('product_purchases')->where('user_id', $user_id)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();
    
            return view('user.pembelian.daftar_pembelian')->with('product_purchases', $product_purchases)
            ->with('purchase_product_specifications', $purchase_product_specifications)->with('profile', $profile);
        }
    }

    public function detail_pembelian($product_purchase_id) {
        $purchase_product_specifications = DB::table('purchase_product_specifications')
        ->join('product_purchases', 'purchase_product_specifications.product_purchase_id', '=', 'product_purchases.product_purchase_id')
        ->join('specifications', 'purchase_product_specifications.specification_id', '=', 'specifications.specification_id')
        ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')
        ->orderBy('purchase_product_specification_id', 'desc')->get();

        if(Session::get('toko')){
            $product_purchases = DB::table('product_purchases')->where('product_purchase_id', $product_purchase_id)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();

            return view('user.toko.detail_pembelian')->with('product_purchases', $product_purchases)
            ->with('purchase_product_specifications', $purchase_product_specifications);
        }

        else{
            $user_id = Auth::user()->id;
            $profile = DB::table('profiles')->where('user_id', $user_id)->first();

            $product_purchases = DB::table('product_purchases')->where('user_id', $user_id)->where('product_purchase_id', $product_purchase_id)
            ->join('purchases', 'product_purchases.purchase_id', '=', 'purchases.purchase_id')
            ->join('products', 'product_purchases.product_id', '=', 'products.product_id')->orderBy('product_purchases.product_purchase_id', 'desc')->get();
    
            return view('user.pembelian.detail_pembelian')->with('product_purchases', $product_purchases)
            ->with('purchase_product_specifications', $purchase_product_specifications)->with('profile', $profile);
        }
    }
}
