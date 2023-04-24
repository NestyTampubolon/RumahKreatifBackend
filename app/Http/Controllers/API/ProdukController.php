<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    //
    public function index()
    {
        $products = DB::table('products')->where('is_deleted', 0)->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.product_id')
            ->inRandomOrder()->get();

        return response()->json(
            $products
        );
    }

    public function lihat_produk(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'nama_kategori' => 'required',
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return response()->json(['message' => $val[0]], 400);
        }

        $products = DB::table('products')->where('is_deleted', 0)
            ->where('categories.nama_kategori', '=', $request->nama_kategori)
            ->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.product_id')
            ->inRandomOrder()->get();

        return response()->json(
            $products,
            200
        );
    }


    public function pilih_kategori()
    {
        $categories = DB::table('categories')->orderBy('nama_kategori', 'asc')->get();

        return response()->json(
            $categories
        );
    }


    public function PostTambahProduk(Request $request)
    {
        $toko = DB::table('merchants')->where('user_id', $request->user_id)->value('merchant_id');
        $product_name = $request->product_name;
        $product_description = $request->product_description;
        $price = $request->price;
        $heavy = $request->heavy;
        $kategori_produk_id = DB::table('categories')->where('nama_kategori', $request->kategori)->value('category_id');

        // $product_image = $request->file('product_image');
        // $jumlah_product_image = count($product_image);

        $stok = $request->stok;

        $product_id = DB::table('products')->insertGetId([
            'merchant_id' => $toko,
            'category_id' => $kategori_produk_id,
            'product_name' => $product_name,
            'product_description' => $product_description,
            'price' => $price,
            'heavy' => $heavy,
            'is_deleted' => 0,
        ]);


        // if ($specification_id) {
        //     $jumlah_specification_id_dipilih = count($specification_id);

        //     for ($x = 0; $x < $jumlah_specification_id_dipilih; $x++) {
        //         DB::table('product_specifications')->insert([
        //             'product_id' => $product_id,
        //             'specification_id' => $specification_id[$x],
        //         ]);
        //     }
        // }

        foreach ($request->file('product_image') as $product_image) {
            $nama_product_image = time() . '_' . $product_image->getClientOriginalName();
            $tujuan_upload = './asset/u_file/product_image';
            $product_image->move($tujuan_upload, $nama_product_image);

            DB::table('product_images')->insert([
                'product_id' => $product_id,
                'product_image_name' => $nama_product_image,
            ]);
        }

        DB::table('stocks')->insert([
            'product_id' => $product_id,
            'stok' => $stok,
        ]);

        return response()->json(
            200
        );
    }

    public function produk(Request $request)
    {
        $toko = DB::table('merchants')->where('user_id', $request->user_id)->value('merchant_id');
        $products = DB::table('products')
        ->where('merchant_id', $toko)
        ->where('is_deleted', 0)
        ->orderBy('products.product_id', 'desc')
        ->join('stocks', 'stocks.product_id', '=', 'products.product_id')
        ->join('categories', 'products.category_id', '=', 'categories.category_id')->get();
        
        return response()->json(
            $products
        );  
    }
}
