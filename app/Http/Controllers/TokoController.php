<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class TokoController extends Controller
{
    public function toko() {
        $user_id = Auth::user()->id;

        $cek_verifikasi = DB::table('verify_users')->where('user_id', $user_id)->first();
        $cek_verified = DB::table('verify_users')->where('user_id', $user_id)->where('is_verified', 1)->first();
        $cek_rekening = DB::table('rekenings')->where('user_id', $user_id)->first();
        $cek_merchant = DB::table('merchants')->where('user_id', $user_id)->first();
        
        if(Session::get('masuk_toko')){
            return view('user.toko.toko');
        }

        else{
            return view('user.toko.toko_dash')->with('cek_verifikasi', $cek_verifikasi)->with('cek_verified', $cek_verified)->with('cek_rekening', $cek_rekening)
            ->with('cek_merchant', $cek_merchant);
        }
    }

    public function MasukToko(Request $request){
        request()->validate(
            [
                'password' => 'required',
            ]);

        $user_id = Auth::user()->id;
        $password = $request->password;

        if(Auth::attempt(['id' => $user_id, 'password' => $password])){
            Session::put('masuk_toko', 'Y');
            return redirect('./toko');
        }
        
        else{
            return redirect()->back();
        }          
    }

    public function keluar_toko(Request $request){
        $request->session()->forget('masuk_toko');
        return redirect('./dashboard');     
    }

    public function PostTambahToko(Request $request) {
        $id = Auth::user()->id;
        $nama_merchant = $request -> nama_merchant;
        $deskripsi = $request -> deskripsi;
        $foto_merchant = $request -> file('foto_merchant');

        $nama_foto_merchant = time().'_'.$foto_merchant->getClientOriginalName();
        $tujuan_upload = './asset/u_file/foto_merchant';
        $foto_merchant->move($tujuan_upload,$nama_foto_merchant);

        DB::table('merchants')->insert([
            'user_id' => $id,
            'nama_merchant' => $nama_merchant,
            'deskripsi' => $deskripsi,
            'foto_merchant' => $nama_foto_merchant,
        ]);

        return redirect('./toko');
    }
}
