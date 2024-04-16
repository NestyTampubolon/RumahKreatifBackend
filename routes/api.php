<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\AutentikasiController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PengirimanController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\DaftarTokoController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\MerchantController;
use App\Http\Controllers\API\RekeningController;
use App\Http\Controllers\API\PembelianController;
use App\Http\Controllers\API\AlamatController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::post('/ubahprofil', [UserController::class, 'PostEditProfil']);
Route::post('/ubahpassword', [UserController::class, 'ubahPassword']);

Route::get('/produk', [ProdukController::class, 'index']);
Route::post('/lihat_produk', [ProdukController::class, 'lihat_produk']);

//merchants
Route::post('/tambahproduk', [ProdukController::class, 'PostTambahProduk']);
Route::get('/pilihkategori', [ProdukController::class, 'pilih_kategori']);
Route::post('/daftarproduk', [ProdukController::class, 'produk']);
Route::post('/hapusproduk', [ProdukController::class, 'hapusProduk']);
Route::post('/produkdetail', [ProdukController::class, 'produk_detail']);
Route::post('/editproduk', [ProdukController::class, 'editProduk']);

Route::post('keranjang', [CartController::class, 'keranjang']);
Route::post('tambahkeranjang', [CartController::class, 'masuk_keranjang']);
Route::post('hapus', [CartController::class, 'hapus']);
Route::post('kurang', [CartController::class, 'kurang']);
Route::post('tambah', [CartController::class, 'tambah']);

Route::post('register', [AutentikasiController::class, 'Register']);
Route::post('login', [AutentikasiController::class, 'PostLogin']);

Route::post('pengiriman', [PengirimanController::class, 'PostBeliProduk']);
Route::post('belilangsung', [PengirimanController::class, 'belilangsung']);
Route::post('daftar_pembelian', [PengirimanController::class, 'daftar_pembelian']);
Route::post('menunggu_pembayaran', [PengirimanController::class, 'menunggu_pembayaran']);
Route::post('detail_pesanan', [PengirimanController::class, 'detail_pesanan']);
Route::post('hapuspesanan', [PengirimanController::class, 'hapus']);
Route::post('PostBuktiPembayaran', [PengirimanController::class, 'PostBuktiPembayaran']);


//wishlist
Route::post('tambahwishlist', [WishlistController::class, 'tambahWishlist']);
Route::post('daftarwishlist', [WishlistController::class, 'daftarWishlist']);
Route::post('hapuswishlist', [WishlistController::class, 'hapusWishlist']);

//toko
Route::post('verifikasitoko', [DaftarTokoController::class, 'PostVerifikasi']);
Route::post('cekverifikasi', [DaftarTokoController::class, 'cekVerifikasi']);
Route::post('cekverifikasiToko', [DaftarTokoController::class, 'cekVerifikasi']);

Route::post('hometoko', [HomeController::class, 'dashboard']);

Route::post('postrekening', [DaftarTokoController::class, 'PostRekening']);
Route::post('posttambahtoko', [DaftarTokoController::class, 'PostTambahToko']);
Route::post('masuktoko', [DaftarTokoController::class, 'MasukToko']);
Route::post('ubahtoko', [DaftarTokoController::class, 'ubahToko']);

Route::post('profilmerchant', [MerchantController::class, 'index']);

Route::get('daftarbank', [RekeningController::class, 'daftarbank']);
Route::post('daftarrekening', [RekeningController::class, 'daftar_rekening']);
Route::post('hapusrekening', [RekeningController::class, 'HapusRekening']);

Route::post('daftarpembelian', [PembelianController::class, 'daftar_pembelian']);
Route::post('detailpembelian', [PembelianController::class, 'detail_pembelian']);
Route::post('updatestatuspembelian', [PembelianController::class, 'update_status_pembelian']);
Route::post('updatestatus2pembelian', [PembelianController::class, 'update_status2_pembelian']);

Route::post('alamatpengguna ', [AlamatController::class, 'AlamatPengguna']);
Route::post('tambahalamat ', [AlamatController::class, 'TambahAlamat']);
Route::post('hapusalamat ', [AlamatController::class, 'HapusAlamat']);

Route::post('alamattoko ', [AlamatController::class, 'AlamatToko']);
Route::post('tambahalamattoko', [AlamatController::class, 'TambahAlamatToko']);
Route::post('hapusalamattoko ', [AlamatController::class, 'HapusAlamatToko']);

Route::post('alamatpengguna_now ', [AlamatController::class, 'AlamatPenggunaPilih']);

Route::get('/pembelian', [PembelianController::class, 'daftarPembelianApi']);
Route::get('/daftartenant', [MerchantController::class, 'daftartenant']);
