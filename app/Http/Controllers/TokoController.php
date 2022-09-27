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
        $cek_merchant_verified = DB::table('merchants')->where('user_id', $user_id)->where('is_verified', 1)->first();
        
        if(Session::get('toko')){
            $merchants = DB::table('merchants')->join('users', 'merchants.user_id', '=', 'users.id')
            ->join('profiles', 'merchants.user_id', '=', 'profiles.user_id')->where('merchants.user_id', $user_id)->get();

            return view('user.toko.toko')->with('merchants', $merchants);
        }

        else{
            return view('user.toko.toko_dash')->with('cek_verifikasi', $cek_verifikasi)->with('cek_verified', $cek_verified)->with('cek_rekening', $cek_rekening)
            ->with('cek_merchant', $cek_merchant) ->with('cek_merchant_verified', $cek_merchant_verified);
        }
    }

    public function PostTambahToko(Request $request) {
        $id = Auth::user()->id;
        $nama_merchant = $request -> nama_merchant;
        $deskripsi_toko = $request -> deskripsi_toko;
        $kontak_toko = $request -> kontak_toko;
        $foto_merchant = $request -> file('foto_merchant');

        $nama_foto_merchant = time().'_'.$foto_merchant->getClientOriginalName();
        $tujuan_upload = './asset/u_file/foto_merchant';
        $foto_merchant->move($tujuan_upload,$nama_foto_merchant);

        DB::table('merchants')->insert([
            'user_id' => $id,
            'nama_merchant' => $nama_merchant,
            'deskripsi_toko' => $deskripsi_toko,
            'kontak_toko' => $kontak_toko,
            'foto_merchant' => $nama_foto_merchant,
        ]);

        return redirect('./toko');
    }

    public function VerifyToko($merchant_id) {
        DB::table('merchants')->where('merchant_id', $merchant_id)->update([
            'is_verified' => 1,
        ]);

        return redirect('./toko_user');
    }

    public function MasukToko(Request $request){
        request()->validate(
            [
                'password' => 'required',
            ]);

        $user_id = Auth::user()->id;
        $password = $request->password;

        $toko = DB::table('merchants')->where('user_id', $user_id)->first();

        if(Auth::attempt(['id' => $user_id, 'password' => $password])){
            Session::put('toko', $toko->merchant_id);
            return redirect('./toko');
        }
        
        else{
            return redirect()->back();
        }          
    }

    public function keluar_toko(Request $request){
        $request->session()->forget('toko');
        return redirect('./dashboard');     
    }

    public function TokoUser(Request $request) {
        $merchants = DB::table('merchants')->join('users', 'merchants.user_id', '=', 'users.id')
        ->join('profiles', 'merchants.user_id', '=', 'profiles.user_id')->orderBy('merchants.user_id', 'asc')->get();

        return view('admin.toko_user')->with('merchants', $merchants);
    }
}
