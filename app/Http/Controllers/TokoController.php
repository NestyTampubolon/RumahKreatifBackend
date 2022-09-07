<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;

class TokoController extends Controller
{
    public function toko() {
        $user_id = Session::get('id');

        $cek_verifikasi = DB::table('verify_users')->where('user_id', $user_id)->first();

        return view('user.toko.toko_dash')->with('cek_verifikasi', $cek_verifikasi);
    }
}
