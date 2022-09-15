<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class ProdukController extends Controller
{
    public function produk() {
        $products = DB::table('products')->orderBy('product_id', 'desc')
        ->join('product_categories', 'products.category_id', '=', 'product_categories.category_id')->get();

        return view('user.toko.produk')->with('products', $products);
    }

    public function pilih_kategori() {
        $product_categories = DB::table('product_categories')->orderBy('nama_kategori', 'asc')->get();

        return view('user.toko.pilih_kategori')->with('product_categories', $product_categories);
    }

    public function tambah_produk($kategori_produk_id) {
        $category_type_specifications = DB::table('category_type_specifications')
        ->join('product_categories', 'category_type_specifications.category_id', '=', 'product_categories.category_id')
        ->join('specification_types', 'category_type_specifications.specification_type_id', '=', 'specification_types.specification_type_id')
        ->where('category_type_specifications.category_id', $kategori_produk_id)->orderBy('category_type_specification_id', 'asc')->get();
        $specifications = DB::table('specifications')->orderBy('nama_spesifikasi', 'asc')->get();
        return view('user.toko.tambah_produk')->with('category_type_specifications', $category_type_specifications)->with('specifications', $specifications)
        ->with('kategori_produk_id', $kategori_produk_id);
    }

    public function PostTambahProduk(Request $request, $kategori_produk_id) {
        // $request -> validate([
        //     'merchant_id' => 'required',
        //     'product_name' => 'required',
        //     'price' => 'required',
        //     'product_image' => 'required',
        //     'specification_id' => 'required',
        // ]);

        $toko = Session::get('toko');
        $product_name = $request -> product_name;
        $product_description = $request -> product_description;
        $price = $request -> price;
        $product_image = $request -> file('product_image');

        $nama_product_image = time().'_'.$product_image->getClientOriginalName();
        $tujuan_upload = './asset/u_file/product_image';
        $product_image->move($tujuan_upload,$nama_product_image);
        
        $specification_id = $request -> specification_id;

        // dd($toko, $product_name, $price, $nama_product_image, $specification_id);

        DB::table('products')->insert([
            'merchant_id' => $toko,
            'category_id' => $kategori_produk_id,
            'product_name' => $product_name,
            'product_description' => $product_description,
            'price' => $price,
            'product_image' => $nama_product_image,
        ]);
        
        // $product_id = DB::table('products')->select('product_id')->pluck('product_id');
        $product_id = DB::table('products')->select('product_id')->orderBy('product_id', 'desc')->first();

        $jumlah_specification_type_id_id_dipilih = count($specification_id);
 
        for($x=0;$x<$jumlah_specification_type_id_id_dipilih;$x++){
            DB::table('product_specifications')->insert([
                'product_id' => $product_id->product_id,
                'specification_id' => $specification_id[$x],
            ]);
        }

        return redirect('./toko');
    }
}
