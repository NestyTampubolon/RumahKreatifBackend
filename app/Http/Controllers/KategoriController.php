<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class KategoriController extends Controller
{
    public function kategori_produk() {
        $product_categories = DB::table('product_categories')->orderBy('nama_kategori', 'asc')->get();

        return view('admin.kategori_produk')->with('product_categories', $product_categories);
    }

    public function PostTambahKategoriProduk(Request $request) {
        $nama_kategori = $request -> nama_kategori;

        DB::table('product_categories')->insert([
            'nama_kategori' => $nama_kategori,
        ]);

        return redirect('./kategori_produk');
    }

    public function PostEditKategoriProduk(Request $request, $kategori_produk_id) {
        $nama_kategori = $request -> nama_kategori;

        DB::table('product_categories')->where('id', $kategori_produk_id)->update([
            'nama_kategori' => $nama_kategori,
        ]);

        return redirect('./kategori_produk');
    }
    
    public function HapusKategoriProduk($kategori_produk_id)
    {
        DB::table('product_categories')->where('id', $kategori_produk_id)->delete();
        
        return redirect('./kategori_produk');
    }

    public function kategori_tipe_spesifikasi() {
        $category_type_specifications = DB::table('category_type_specifications')
        ->join('product_categories', 'category_type_specifications.category_id', '=', 'product_categories.category_id')
        ->orderBy('category_type_specification_id', 'asc')->get();

        $product_categories = DB::table('product_categories')->orderBy('nama_kategori', 'asc')->get();
        $specification_types = DB::table('specification_types')->orderBy('nama_jenis_spesifikasi', 'asc')->get();
        
        // $cek_category = DB::table('category_type_specifications')->select('category_id')->first();
        // $cek_specification_types = DB::table('category_type_specifications')->select('specification_type_id')->first();

        // tipe data string
        $specification_type_id = DB::table('category_type_specifications')->select('specification_type_id')->get();

        // tipe data arr
        foreach($specification_type_id as $specification_type_id) {
            $specification_type_id_arr = explode(",", $specification_type_id->specification_type_id);
            
            $nama_jenis_spesifikasi = DB::table('category_type_specifications')->select('nama_jenis_spesifikasi')
            ->join('specification_types', 'category_type_specifications.specification_type_id', '=', 'specification_types.specification_type_id')
            ->where('category_type_specifications.specification_type_id', $specification_type_id->specification_type_id)->get();
        }

        dd($specification_type_id_arr);


        return view('admin.kategori_tipe_spesifikasi')->with('category_type_specifications', $category_type_specifications)->with('product_categories', $product_categories)
        ->with('specification_types', $specification_types)->with('nama_jenis_spesifikasi', $nama_jenis_spesifikasi);
    }
    
    public function PostTambahKategoriTipeSpesifikasi(Request $request) {
        $request -> validate([
            'category_id' => 'required|unique:category_type_specifications',
            'specification_type_id' => 'required',
        ]);

        $category_id = $request -> category_id;
        $specification_type_id = $request -> specification_type_id;

        DB::table('category_type_specifications')->insert([
            'category_id' => $category_id,
            'specification_type_id' => implode(",", $specification_type_id),
        ]);

        // $category_type_specification_id = DB::table('category_type_specifications')->select('category_type_specification_id')->pluck('category_type_specification_id');
        
        // $category_type_specification_id = DB::table('category_type_specifications')->select('category_type_specification_id')->orderBy('category_type_specification_id', 'desc')->limit(1)->first();

        // DB::table('category_type_specifications')->where('category_type_specification_id', $category_type_specification_id->category_type_specification_id)->update([
        //     'specification_type_id' => implode(",", $specification_type_id),
        // ]);

        return redirect('./kategori_tipe_spesifikasi');
    }

}
