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
        $verify_users = DB::table('verify_users')->join('users', 'verify_users.user_id', '=', 'users.id')->orderBy('verify_users.user_id', 'asc')
        ->join('profiles', 'verify_users.user_id', '=', 'profiles.user_id')->orderBy('verify_users.user_id', 'asc')->get();

        return view('admin.verifikasi_user')->with('verify_users', $verify_users);
    }

    public function VerifyUser($user_id) {
        DB::table('verify_users')->where('user_id', $user_id)->update([
            'is_verified' => 1,
        ]);

        return redirect('./admin.verifikasi_user');
    }

}
