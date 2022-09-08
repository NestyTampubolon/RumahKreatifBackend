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
}
