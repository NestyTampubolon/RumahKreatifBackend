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
        ->where('categories.nama_kategori','=', $request->nama_kategori)
        ->join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')
        ->join('stocks', 'stocks.product_id', '=', 'products.product_id')
        ->inRandomOrder()->get();

        return response()->json(
            $products, 200
        );
    }
}
