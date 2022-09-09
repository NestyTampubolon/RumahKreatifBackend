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

Route::get('/', 'App\Http\Controllers\HomeController@index');

Route::get('/dashboard', 'App\Http\Controllers\HomeController@dashboard');

Route::get('/toko', 'App\Http\Controllers\TokoController@toko');

Route::post('/MasukToko', 'App\Http\Controllers\TokoController@MasukToko');
Route::get('/keluar_toko', 'App\Http\Controllers\TokoController@keluar_toko');
Route::post('/PostTambahToko', 'App\Http\Controllers\TokoController@PostTambahToko');

Route::get('/toko_user', 'App\Http\Controllers\TokoController@TokoUser');
Route::get('/verify_toko/{id}', 'App\Http\Controllers\TokoController@VerifyToko');

Route::get('/verifikasi', function () {
    return view('user.verifikasi');
});
Route::post('/PostVerifikasi', 'App\Http\Controllers\VerifikasiController@PostVerifikasi');

Route::get('/verifikasi_user', 'App\Http\Controllers\VerifikasiController@VerifikasiUser');
Route::get('/verify_user/{id}', 'App\Http\Controllers\VerifikasiController@VerifyUser');

Route::get('/bank', 'App\Http\Controllers\BankController@bank');
Route::post('/PostTambahBank', 'App\Http\Controllers\BankController@PostTambahBank');
Route::post('/PostEditBank/{bank_id}', 'App\Http\Controllers\BankController@PostEditBank');
Route::get('/hapus_bank/{bank_id}', 'App\Http\Controllers\BankController@HapusBank');

Route::get('/rekening', 'App\Http\Controllers\RekeningController@rekening');
Route::post('/PostRekening', 'App\Http\Controllers\RekeningController@PostRekening');

Route::post('/registrasi', 'App\Http\Controllers\AutentikasiController@PostRegister');
Route::post('/login', 'App\Http\Controllers\AutentikasiController@PostLogin');
Route::get('/logout', 'App\Http\Controllers\AutentikasiController@Logout');