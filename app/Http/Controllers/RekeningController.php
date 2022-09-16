<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class RekeningController extends Controller
{
    public function rekening() {
        $banks = DB::table('banks')->orderBy('nama_bank', 'asc')->get();

        return view('user.rekening')->with('banks', $banks);
    }

    public function PostRekening(Request $request) {
        $id = Auth::user()->id;
        $nama_bank = $request -> nama_bank;
        $nomor_rekening = $request -> nomor_rekening;
        $atas_nama = $request -> atas_nama;

        DB::table('rekenings')->insert([
            'user_id' => $id,
            'nama_bank' => $nama_bank,
            'nomor_rekening' => $nomor_rekening,
            'atas_nama' => $atas_nama,
        ]);

        return redirect('./toko');
    }

    public function daftar_rekening() {
        $is_admin = Auth::user()->is_admin;

        if($is_admin == 1){
            $rekenings = DB::table('rekenings')->join('users', 'rekenings.user_id', '=', 'users.id')->orderBy('rekenings.user_id', 'desc')->orderBy('rekening_id', 'asc')->get();
            $profiles = DB::table('profiles')->orderBy('profile_id', 'asc')->get();

            return view('admin.daftar_rekening')->with('rekenings', $rekenings)->with('profiles', $profiles);
        }

        else{
            $user_id = Auth::user()->id;
            $rekenings = DB::table('rekenings')->where('user_id', $user_id)->orderBy('rekening_id', 'asc')->get();

            return view('user.toko.daftar_rekening')->with('rekenings', $rekenings);
        }   
    }

    public function HapusRekening($rekening_id)
    {
        DB::table('rekenings')->where('rekening_id', $rekening_id)->delete();
        
        return redirect('./daftar_rekenings');
    }
}
