<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    //
    public function index()
    {
        // $products = DB::table('products')->get();
        // return response()->json(
        //     $products
        // );

        $toko = Session::get('toko');

        if ($toko) {
            $products = DB::table('products')->where('merchant_id', $toko)->where('is_deleted', 0)->orderBy('product_id', 'desc')
                ->join('categories', 'products.category_id', '=', 'categories.category_id')->get();

            return view('user.toko.produk')->with('products', $products);
        } else if (!$toko) {
            $kategori_produk_id = 0;

            $products = DB::table('products')->where('is_deleted', 0)->join('categories', 'products.category_id', '=', 'categories.category_id')
                ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->inRandomOrder()->get();

            $product_info = DB::table('products')->orderBy('product_id', 'desc')->join('categories', 'products.category_id', '=', 'categories.category_id')
                ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->first();

            $categories = DB::table('categories')->orderBy('nama_kategori', 'asc')->get();

            // $nama_kategori = DB::table('categories')->where('category_id', $kategori_produk_id)->first();
            return response()->json(
                $products
            );
        }
    }

    public function lihat_produk($product_id)
    {
        $product = DB::table('products')->where('product_id', $product_id)->where('is_deleted', 0)->join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->orderBy('product_id', 'desc')->first();

        $product_images = DB::table('product_images')->where('product_id', $product_id)->orderBy('product_image_id', 'asc')->get();

        // $product_category_id = DB::table('products')->select('category_id')->where('product_id', $product_id)->first();

        $product_specifications = DB::table('product_specifications')
        ->join('products', 'product_specifications.product_id', '=', 'products.product_id')
        ->join('specifications', 'product_specifications.specification_id', '=', 'specifications.specification_id')
        ->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')
        ->where('product_specifications.product_id', $product_id)->get();

        $stocks = DB::table('stocks')->where('product_id', $product_id)->first();

        $cek_merchant_address = DB::table('merchant_address')->where('merchant_id', $product->merchant_id)->count();

        $merchant_address = DB::table('merchant_address')->where('merchant_id', $product->merchant_id)->first();

        $reviews = DB::table('reviews')->where('product_id', $product_id)->join('profiles', 'reviews.user_id', '=', 'profiles.user_id')->orderBy('review_id', 'desc')->get();
        $jumlah_review = DB::table('reviews')->where('product_id', $product_id)->count();


        if ($cek_merchant_address > 0) {

            $curl = curl_init();

            $param = $merchant_address->city_id;
            $subdistrict_id = $merchant_address->subdistrict_id;

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=" . $param,
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
            $filters =  array_filter($collection['rajaongkir']['results'], function ($r) use ($subdistrict_id) {
                return $r['subdistrict_id'] == $subdistrict_id;
            });

            foreach ($filters as $filter) {
                $lokasi_toko = $filter;
            }

            $err = curl_error($curl);
            curl_close($curl);
        } else {
            $lokasi_toko = "";
        }

        // if (Auth::check()) {
        //     $user_id = Auth::user()->id;
        //     $cek_alamat = DB::table('user_address')->where('user_id', $user_id)->first();
        //     $cek_review = DB::table('reviews')->where('user_id', $user_id)->where('product_id', $product_id)->first();

        //     return view('user.lihat_produk', compact(['product', 'product_images', 'product_specifications', 'stocks', 'cek_merchant_address', 'merchant_address', 'reviews', 'jumlah_review', 'cek_alamat', 'cek_review', 'lokasi_toko']));
        // } else {
        //     return view('user.lihat_produk', compact(['product', 'product_images', 'product_specifications', 'stocks', 'cek_merchant_address', 'merchant_address', 'reviews', 'jumlah_review', 'lokasi_toko']));
        // }

        return response()->json(
            array(
                $product
            )
        );
    }
}
