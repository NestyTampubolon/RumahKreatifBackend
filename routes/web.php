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

// Route::get('/mail_message','App\Http\Controllers\MailController@index');

Route::get('/profil', 'App\Http\Controllers\ProfilController@profil');
Route::get('/edit_profil', 'App\Http\Controllers\ProfilController@edit_profil');
Route::post('/PostEditProfil', 'App\Http\Controllers\ProfilController@PostEditProfil');

Route::get('/alamat', 'App\Http\Controllers\AlamatController@alamat');
Route::post('/PostAlamat', 'App\Http\Controllers\AlamatController@PostAlamat');
Route::get('/daftar_alamat', 'App\Http\Controllers\AlamatController@daftar_alamat');
Route::get('/hapus_alamat/{address_id}', 'App\Http\Controllers\AlamatController@HapusAlamat');

Route::get('/pengiriman_lokal', 'App\Http\Controllers\PengirimanLokalController@pengiriman_lokal');
Route::post('/PostPengirimanLokal', 'App\Http\Controllers\PengirimanLokalController@PostPengirimanLokal');
Route::get('/daftar_pengiriman_lokal', 'App\Http\Controllers\PengirimanLokalController@daftar_pengiriman_lokal');
Route::get('/hapus_pengiriman_lokal/{shipping_local_id}', 'App\Http\Controllers\PengirimanLokalController@HapusPengirimanLokal');

Route::get('/ambil_lokasi', 'App\Http\Controllers\AlamatController@ambil_lokasi');


Route::get('/', 'App\Http\Controllers\HomeController@index');

Route::get('/dashboard', 'App\Http\Controllers\HomeController@dashboard');

Route::get('/toko', 'App\Http\Controllers\TokoController@toko');
Route::get('/edit_toko', 'App\Http\Controllers\TokoController@edit_toko');
Route::post('/PostEditToko', 'App\Http\Controllers\TokoController@PostEditToko');

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

Route::get('/user', 'App\Http\Controllers\VerifikasiController@VerifikasiUser');
Route::get('/user/{verify_id}', 'App\Http\Controllers\VerifikasiController@VerifyUser');

Route::get('/bank', 'App\Http\Controllers\BankController@bank');
Route::post('/PostTambahBank', 'App\Http\Controllers\BankController@PostTambahBank');
Route::post('/PostEditBank/{bank_id}', 'App\Http\Controllers\BankController@PostEditBank');
Route::get('/hapus_bank/{bank_id}', 'App\Http\Controllers\BankController@HapusBank');

Route::get('/rekening', 'App\Http\Controllers\RekeningController@rekening');
Route::post('/PostRekening', 'App\Http\Controllers\RekeningController@PostRekening');
Route::get('/daftar_rekening', 'App\Http\Controllers\RekeningController@daftar_rekening');
Route::get('/hapus_rekening/{bank_id}', 'App\Http\Controllers\RekeningController@HapusRekening');

Route::get('/produk', 'App\Http\Controllers\ProdukController@produk');
Route::post('/cari_produk', 'App\Http\Controllers\ProdukController@cari_produk');
Route::get('/cari_produk/{cari_produk}', 'App\Http\Controllers\ProdukController@cari_produk_view');
Route::get('/lihat_produk/{product_id}', 'App\Http\Controllers\ProdukController@lihat_produk');
Route::get('/produk/kategori[{kategori_produk_id}]', 'App\Http\Controllers\ProdukController@produk_kategori');
Route::get('/produk/toko[{merchant_id}]', 'App\Http\Controllers\ProdukController@produk_toko_belanja');

Route::get('/tambah_produk/pilih_kategori', 'App\Http\Controllers\ProdukController@pilih_kategori');
Route::get('/tambah_produk/{kategori_produk_id}', 'App\Http\Controllers\ProdukController@tambah_produk');
Route::post('/PostTambahProduk/{kategori_produk_id}', 'App\Http\Controllers\ProdukController@PostTambahProduk');
Route::get('/edit_produk/{product_id}', 'App\Http\Controllers\ProdukController@edit_produk');
Route::post('/PostEditProduk/{product_id}', 'App\Http\Controllers\ProdukController@PostEditProduk');
Route::get('/HapusProduk/{product_id}', 'App\Http\Controllers\ProdukController@HapusProduk');

Route::get('/produk_toko', 'App\Http\Controllers\ProdukController@produk_toko');

Route::post('/checkout/{merchant_id}', 'App\Http\Controllers\PembelianController@checkout');

Route::get('/pilih_metode_pembelian', 'App\Http\Controllers\PembelianController@pilih_metode_pembelian');

Route::get('/ambil_jalan', 'App\Http\Controllers\PembelianController@ambil_jalan');

Route::post('/cek_ongkir', 'App\Http\Controllers\PembelianController@cek_ongkir');

Route::get('/voucher', 'App\Http\Controllers\VoucherController@voucher');
Route::get('/pilih_tipe_voucher', 'App\Http\Controllers\VoucherController@pilih_tipe_voucher');
// Route::get('/pilih_target_kategori_voucher', 'App\Http\Controllers\VoucherController@pilih_target_kategori_voucher');
Route::post('/PostTambahVoucher', 'App\Http\Controllers\VoucherController@PostTambahVoucher');
Route::get('/hapus_voucher/{voucher_id}', 'App\Http\Controllers\VoucherController@HapusVoucher');

Route::get('/ambil_voucher_pembelian', 'App\Http\Controllers\PembelianController@ambil_voucher_pembelian');
Route::get('/ambil_voucher_ongkos_kirim', 'App\Http\Controllers\PembelianController@ambil_voucher_ongkos_kirim');

Route::post('/PostBeliProduk', 'App\Http\Controllers\PembelianController@PostBeliProduk');
Route::get('/daftar_pembelian', 'App\Http\Controllers\PembelianController@daftar_pembelian');
Route::get("/purchase/detail/{id}", "App\Http\Controllers\PembelianController@detail_purchase");
Route::get('/detail_pembelian/{purchase_id}', 'App\Http\Controllers\PembelianController@detail_pembelian');
Route::get('/batalkan_pembelian/{purchase_id}', 'App\Http\Controllers\PembelianController@batalkan_pembelian');

Route::get('/invoice_pembelian/{purchase_id}', 'App\Http\Controllers\InvoiceController@invoice_pembelian');
Route::get('/invoice_penjualan/{purchase_id}', 'App\Http\Controllers\InvoiceController@invoice_penjualan');

Route::post('/PostBuktiPembayaran/{purchase_id}', 'App\Http\Controllers\PembelianController@PostBuktiPembayaran');
Route::get('/update_status_pembelian/{purchase_id}', 'App\Http\Controllers\PembelianController@update_status_pembelian');
Route::post('/update_status2_pembelian/{purchase_id}', 'App\Http\Controllers\PembelianController@update_status2_pembelian');
Route::post('/update_no_resi/{purchase_id}', 'App\Http\Controllers\PembelianController@update_no_resi');

Route::get('/keranjang', 'App\Http\Controllers\KeranjangController@keranjang');
// Route::get('/masuk_keranjang/{product_id}', 'App\Http\Controllers\KeranjangController@masuk_keranjang');
Route::get('/masuk_keranjang', 'App\Http\Controllers\KeranjangController@masuk_keranjang');
Route::post('/masuk_keranjang_beli/{product_id}', 'App\Http\Controllers\KeranjangController@masuk_keranjang_beli');
Route::get('/hapus_keranjang/{cart_id}', 'App\Http\Controllers\KeranjangController@HapusKeranjang');

Route::get('/carousel', 'App\Http\Controllers\CarouselController@carousel');
Route::post('/PostTambahCarousel', 'App\Http\Controllers\CarouselController@PostTambahCarousel');
Route::post('/PostEditCarousel/{id}', 'App\Http\Controllers\CarouselController@PostEditCarousel');
Route::get('/hapus_carousel/{id}', 'App\Http\Controllers\CarouselController@HapusCarousel');

Route::post('/PostTinjauan/{product_id}', 'App\Http\Controllers\TinjauanController@PostTinjauan');

Route::get('/panduan_penggunaan', function () {
    return view('user.panduan_penggunaan');
});

Route::get('/privacy', 'App\Http\Controllers\PrivacyController@privacy');