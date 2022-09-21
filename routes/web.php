<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::post('/registrasi', 'App\Http\Controllers\AutentikasiController@PostRegister');
Route::post('/login', 'App\Http\Controllers\AutentikasiController@PostLogin');
Route::get('/logout', 'App\Http\Controllers\AutentikasiController@Logout');

Route::get('/', 'App\Http\Controllers\HomeController@index');

Route::get('/dashboard', 'App\Http\Controllers\HomeController@dashboard');

Route::get('/toko', 'App\Http\Controllers\TokoController@toko');

Route::post('/MasukToko', 'App\Http\Controllers\TokoController@MasukToko');
Route::get('/keluar_toko', 'App\Http\Controllers\TokoController@keluar_toko');
Route::post('/PostTambahToko', 'App\Http\Controllers\TokoController@PostTambahToko');

Route::get('/toko_user', 'App\Http\Controllers\TokoController@TokoUser');
Route::get('/verify_toko/{merchant_id}', 'App\Http\Controllers\TokoController@VerifyToko');

Route::get('/tipe_spesifikasi', 'App\Http\Controllers\SpesifikasiController@tipe_spesifikasi');
Route::post('/PostTambahTipeSpesifikasi', 'App\Http\Controllers\SpesifikasiController@PostTambahTipeSpesifikasi');
Route::post('/PostEditTipeSpesifikasi/{specification_type_id}', 'App\Http\Controllers\SpesifikasiController@PostEditTipeSpesifikasi');
// Route::get('/hapus_tipe_spesifikasi/{specification_type_id}', 'App\Http\Controllers\SpesifikasiController@HapusTipeSpesifikasi');

Route::get('/spesifikasi', 'App\Http\Controllers\SpesifikasiController@spesifikasi');
Route::post('/PostTambahSpesifikasi', 'App\Http\Controllers\SpesifikasiController@PostTambahSpesifikasi');
Route::post('/PostEditSpesifikasi/{specification_id}', 'App\Http\Controllers\SpesifikasiController@PostEditSpesifikasi');
// Route::get('/hapus_spesifikasi/{specification_id}', 'App\Http\Controllers\SpesifikasiController@HapusSpesifikasi');

Route::get('/kategori_produk', 'App\Http\Controllers\KategoriController@kategori_produk');
Route::post('/PostTambahKategoriProduk', 'App\Http\Controllers\KategoriController@PostTambahKategoriProduk');
Route::post('/PostEditKategoriProduk/{kategori_produk_id}', 'App\Http\Controllers\KategoriController@PostEditKategoriProduk');
// Route::get('/hapus_kategori_produk/{kategori_produk_id}', 'App\Http\Controllers\KategoriController@HapusKategoriProduk');

Route::get('/kategori_tipe_spesifikasi', 'App\Http\Controllers\KategoriController@kategori_tipe_spesifikasi');
Route::post('/PostTambahKategoriTipeSpesifikasi', 'App\Http\Controllers\KategoriController@PostTambahKategoriTipeSpesifikasi');
Route::post('/PostEditKategoriTipeSpesifikasi/{category_type_specification_id}', 'App\Http\Controllers\KategoriController@PostEditKategoriTipeSpesifikasi');
// Route::get('/hapus_kategori_tipe_spesifikasi/{category_type_specification_id}', 'App\Http\Controllers\KategoriController@HapusKategoriTipeSpesifikasi');

Route::get('/verifikasi', function () {
    return view('user.verifikasi');
});
Route::post('/PostVerifikasi', 'App\Http\Controllers\VerifikasiController@PostVerifikasi');

Route::get('/verifikasi_user', 'App\Http\Controllers\VerifikasiController@VerifikasiUser');
Route::get('/verify_user/{verify_id}', 'App\Http\Controllers\VerifikasiController@VerifyUser');

Route::get('/bank', 'App\Http\Controllers\BankController@bank');
Route::post('/PostTambahBank', 'App\Http\Controllers\BankController@PostTambahBank');
Route::post('/PostEditBank/{bank_id}', 'App\Http\Controllers\BankController@PostEditBank');
Route::get('/hapus_bank/{bank_id}', 'App\Http\Controllers\BankController@HapusBank');

Route::get('/rekening', 'App\Http\Controllers\RekeningController@rekening');
Route::post('/PostRekening', 'App\Http\Controllers\RekeningController@PostRekening');

Route::get('/daftar_rekening', 'App\Http\Controllers\RekeningController@daftar_rekening');
Route::get('/hapus_rekening/{bank_id}', 'App\Http\Controllers\RekeningController@HapusRekening');

Route::get('/produk', 'App\Http\Controllers\ProdukController@produk');
Route::get('/tambah_produk/pilih_kategori', 'App\Http\Controllers\ProdukController@pilih_kategori');
Route::get('/tambah_produk/{kategori_produk_id}', 'App\Http\Controllers\ProdukController@tambah_produk');
Route::post('/PostTambahProduk/{kategori_produk_id}', 'App\Http\Controllers\ProdukController@PostTambahProduk');

Route::get('/lihat_produk/{product_id}', 'App\Http\Controllers\ProdukController@lihat_produk');

Route::post('/PostBeliProduk/{product_id}', 'App\Http\Controllers\PembelianController@PostBeliProduk');
Route::get('/daftar_pembelian', 'App\Http\Controllers\PembelianController@daftar_pembelian');
Route::get('/detail_pembelian/{product_purchase_id}', 'App\Http\Controllers\PembelianController@detail_pembelian');

Route::get('/keranjang', 'App\Http\Controllers\KeranjangController@keranjang');
Route::get('/masuk_keranjang/{product_id}', 'App\Http\Controllers\KeranjangController@masuk_keranjang');
Route::get('/hapus_keranjang/{cart_id}', 'App\Http\Controllers\KeranjangController@HapusKeranjang');