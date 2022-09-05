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

Route::post('/registrasi', 'App\Http\Controllers\AutentikasiController@PostRegister');
Route::post('/login', 'App\Http\Controllers\AutentikasiController@PostLogin');
Route::get('/logout', 'App\Http\Controllers\AutentikasiController@Logout');