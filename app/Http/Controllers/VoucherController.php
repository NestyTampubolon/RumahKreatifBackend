<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class VoucherController extends Controller
{
    public function voucher() {
        $vouchers = DB::table('vouchers')->orderBy('nama_voucher', 'asc')->get();

        return view('admin.voucher')->with('vouchers', $vouchers);
    }

    public function PostTambahVoucher(Request $request) {
        $nama_bank = $request -> nama_bank;

        DB::table('banks')->insert([
            'nama_bank' => $nama_bank,
        ]);

        return redirect('./bank');
    }

    public function PostEditVoucher(Request $request, $bank_id) {
        $nama_bank = $request -> nama_bank;

        DB::table('banks')->where('id', $bank_id)->update([
            'nama_bank' => $nama_bank,
        ]);

        return redirect('./bank');
    }

    public function HapusVoucher($bank_id)
    {
        DB::table('banks')->where('id', $bank_id)->delete();
        
        return redirect('./bank');
    }
}
