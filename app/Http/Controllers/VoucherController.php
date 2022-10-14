<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class VoucherController extends Controller
{
    public function voucher() {
        $vouchers = DB::table('vouchers')->where('is_deleted', 0)->where('tanggal_berlaku', '>=', date('Y-m-d'))
        ->where('tanggal_batas_berlaku', '>=', date('Y-m-d'))->orderBy('nama_voucher', 'asc')->get();

        return view('admin.voucher')->with('vouchers', $vouchers);
    }

    public function PostTambahVoucher(Request $request) {
        $nama_voucher = $request -> nama_voucher;
        $potongan = $request -> potongan;
        $minimal_pengambilan = $request -> minimal_pengambilan;
        $maksimal_pemotongan = $request -> maksimal_pemotongan;
        $tanggal_berlaku = $request -> tanggal_berlaku;
        $tanggal_batas_berlaku = $request -> tanggal_batas_berlaku;

        DB::table('vouchers')->insert([
            'nama_voucher' => $nama_voucher,
            'potongan' => $potongan,
            'minimal_pengambilan' => $minimal_pengambilan,
            'maksimal_pemotongan' => $maksimal_pemotongan,
            'tanggal_berlaku' => $tanggal_berlaku,
            'tanggal_batas_berlaku' => $tanggal_batas_berlaku,
            'is_deleted' => 0,
        ]);

        return redirect('./voucher');
    }

    public function HapusVoucher($voucher_id)
    {
        DB::table('vouchers')->where('voucher_id', $voucher_id)->update([
            'is_deleted' => 1,
        ]);
        
        return redirect('./voucher');
    }
}
