<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class BankController extends Controller
{

    public function bank() {
        $banks = DB::table('banks')->orderBy('nama_bank', 'asc')->get();

        return view('admin.bank')->with('banks', $banks);
    }

    public function PostTambahBank(Request $request) {
        $nama_bank = $request -> nama_bank;

        DB::table('banks')->insert([
            'nama_bank' => $nama_bank,
        ]);

        return redirect('./bank');
    }

    public function PostEditBank(Request $request, $bank_id) {
        $nama_bank = $request -> nama_bank;

        DB::table('banks')->where('id', $bank_id)->update([
            'nama_bank' => $nama_bank,
        ]);

        return redirect('./bank');
    }

    public function HapusBank($bank_id)
    {
        DB::table('banks')->where('id', $bank_id)->delete();
        
        return redirect('./bank');
    }
}
