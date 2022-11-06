<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class VerifikasiController extends Controller
{
    public function PostVerifikasi(Request $request) {
        $id = Auth::user()->id;
        $foto_ktp = $request -> file('foto_ktp');
        $ktp_dan_selfie = $request -> file('ktp_dan_selfie');

        $nama_foto_ktp = time().'_'.$foto_ktp->getClientOriginalName();
        $tujuan_upload = './asset/u_file/foto_ktp';
        $foto_ktp->move($tujuan_upload,$nama_foto_ktp);

        $nama_ktp_selfie = time().'_'.$ktp_dan_selfie->getClientOriginalName();
        $tujuan_upload = './asset/u_file/foto_ktp_selfie';
        $ktp_dan_selfie->move($tujuan_upload,$nama_ktp_selfie);

        DB::table('verify_users')->insert([
            'user_id' => $id,
            'foto_ktp' => $nama_foto_ktp,
            'ktp_dan_selfie' => $nama_ktp_selfie,
        ]);

        return redirect('./toko');
    }

    public function VerifikasiUser(Request $request) {
        $profile_users = DB::table('profiles')->join('users', 'profiles.user_id', '=', 'users.id')->get();

        return view('admin.verifikasi_user')->with('profile_users', $profile_users);
    }

    public function VerifyUser($verify_id) {
        DB::table('verify_users')->where('verify_id', $verify_id)->update([
            'is_verified' => 1,
        ]);

        return redirect('./verifikasi_user');
    }

}
