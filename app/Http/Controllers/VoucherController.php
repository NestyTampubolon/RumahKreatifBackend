<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class VoucherController extends Controller
{
    public function voucher() {
        date_default_timezone_set('Asia/Jakarta');
        
        $vouchers = DB::table('vouchers')->where('is_deleted', 0)->orderBy('nama_voucher', 'desc')->get();
        
        $categories = DB::table('categories')->orderBy('nama_kategori', 'asc')->get();

        return view('admin.voucher')->with('vouchers', $vouchers)->with('categories', $categories);
    }

    public function pilih_tipe_voucher() {
        $tipe_voucher = $_GET['tipe'];
        
        return response()->json($tipe_voucher);
    }

    // public function pilih_target_kategori_voucher() {
    //     $target_kategori_voucher = $_GET['target_kategori_voucher'];
        
    //     return response()->json($target_kategori_voucher);
    // }

    public function PostTambahVoucher(Request $request) {
        $nama_voucher = $request -> nama_voucher;
        $tipe_voucher = $request -> tipe_voucher;
        $potongan = $request -> potongan;
        $minimal_pengambilan = $request -> minimal_pengambilan;
        $maksimal_pemotongan = $request -> maksimal_pemotongan;
        $tanggal_berlaku = $request -> tanggal_berlaku;
        $tanggal_batas_berlaku = $request -> tanggal_batas_berlaku;

        if($tipe_voucher == "pembelian"){
            $target_kategori = $request -> target_kategori;
            $target_metode_pembelian = $request -> target_metode_pembelian;
            
            $request -> validate([
                'nama_voucher' => 'required',
                'tipe_voucher' => 'required',
                'target_kategori' => 'required',
                'potongan' => 'required|integer',
                'minimal_pengambilan' => 'required|integer',
                'maksimal_pemotongan' => 'required|integer',
                'tanggal_berlaku' => 'required',
                'tanggal_batas_berlaku' => 'required',
            ]);

            DB::table('vouchers')->insert([
                'nama_voucher' => $nama_voucher,
                'tipe_voucher' => $tipe_voucher,
                'target_kategori' => implode(", ", $target_kategori),
                'target_metode_pembelian' => $target_metode_pembelian,
                'potongan' => $potongan,
                'minimal_pengambilan' => $minimal_pengambilan,
                'maksimal_pemotongan' => $maksimal_pemotongan,
                'tanggal_berlaku' => $tanggal_berlaku,
                'tanggal_batas_berlaku' => $tanggal_batas_berlaku,
                'is_deleted' => 0,
            ]);
        }
        
        else if($tipe_voucher == "ongkos_kirim"){
            $target_metode_pembelian = $request -> target_metode_pembelian;

            $request -> validate([
                'nama_voucher' => 'required',
                'tipe_voucher' => 'required',
                'potongan' => 'required|integer',
                'minimal_pengambilan' => 'required|integer',
                'tanggal_berlaku' => 'required',
                'tanggal_batas_berlaku' => 'required',    
            ]);

            DB::table('vouchers')->insert([
                'nama_voucher' => $nama_voucher,
                'tipe_voucher' => $tipe_voucher,
                'target_metode_pembelian' => $target_metode_pembelian,
                'potongan' => $potongan,
                'minimal_pengambilan' => $minimal_pengambilan,
                'maksimal_pemotongan' => $potongan,
                'tanggal_berlaku' => $tanggal_berlaku,
                'tanggal_batas_berlaku' => $tanggal_batas_berlaku,
                'is_deleted' => 0,
            ]);
        }

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
