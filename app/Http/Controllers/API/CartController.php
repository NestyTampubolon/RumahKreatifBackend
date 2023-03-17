<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    //
    public function keranjang()
    {
        $carts = DB::table('carts')->join('products', 'carts.product_id', '=', 'products.product_id')->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->get();

        return response()->json(
            array(
                $carts
            )
        );
    }
}
