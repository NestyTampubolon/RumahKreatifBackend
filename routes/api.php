<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\CartController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/produkjson', [ProdukController::class, 'index']);
Route::get('/produkjson/{product_id}', [ProdukController::class, 'lihat_produk']);
Route::get('/keranjang', [CartController::class, 'keranjang']);

