<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class TokoController extends Controller
{
    public function toko() {
        $user_id = Auth::user()->id;

        $cek_verifikasi = DB::table('verify_users')->where('user_id', $user_id)->first();
        $cek_verified = DB::table('verify_users')->where('user_id', $user_id)->where('is_verified', 1)->first();
        $cek_rekening = DB::table('rekenings')->where('user_id', $user_id)->first();

        return view('user.toko.toko_dash')->with('cek_verifikasi', $cek_verifikasi)->with('cek_verified', $cek_verified)->with('cek_rekening', $cek_rekening);
    }
}
