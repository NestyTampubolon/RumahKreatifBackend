<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\AutentikasiController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PengirimanController;

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


Route::get('/produk', [ProdukController::class, 'index']);
Route::post('/lihat_produk', [ProdukController::class, 'lihat_produk']);

Route::post('keranjang', [CartController::class, 'keranjang']);
Route::post('tambahkeranjang', [CartController::class, 'masuk_keranjang']);
Route::post('hapus', [CartController::class, 'hapus']);
Route::post('kurang', [CartController::class, 'kurang']);
Route::post('tambah', [CartController::class, 'tambah']);

Route::post('register', [AutentikasiController::class, 'Register']);
Route::post('login', [AutentikasiController::class, 'PostLogin']);


Route::post('pengiriman', [PengirimanController::class, 'PostBeliProduk']);
Route::post('daftar_pembelian', [PengirimanController::class, 'daftar_pembelian']);
Route::post('menunggu_pembayaran', [PengirimanController::class, 'menunggu_pembayaran']);
Route::post('detail_pesanan', [PengirimanController::class, 'detail_pesanan']);
Route::post('PostBuktiPembayaran', [PengirimanController::class, 'PostBuktiPembayaran']);


Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');;
