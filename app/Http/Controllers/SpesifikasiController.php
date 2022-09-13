<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SpesifikasiController extends Controller
{
    public function tipe_spesifikasi() {
        $specification_types = DB::table('specification_types')->orderBy('nama_jenis_spesifikasi', 'asc')->get();

        return view('admin.tipe_spesifikasi')->with('specification_types', $specification_types);
    }

    public function PostTambahTipeSpesifikasi(Request $request) {
        $nama_jenis_spesifikasi = $request -> nama_jenis_spesifikasi;

        DB::table('specification_types')->insert([
            'nama_jenis_spesifikasi' => $nama_jenis_spesifikasi,
        ]);

        return redirect('./tipe_spesifikasi');
    }

    public function PostEditTipeSpesifikasi(Request $request, $specification_type_id) {
        $nama_jenis_spesifikasi = $request -> nama_jenis_spesifikasi;

        DB::table('specification_types')->where('specification_type_id', $specification_type_id)->update([
            'nama_jenis_spesifikasi' => $nama_jenis_spesifikasi,
        ]);

        return redirect('./tipe_spesifikasi');
    }
    
    public function HapusTipeSpesifikasi($specification_type_id)
    {
        DB::table('specification_types')->where('specification_type_id', $specification_type_id)->delete();
        
        return redirect('./tipe_spesifikasi');
    }
    
    public function spesifikasi() {
        $specifications = DB::table('specifications')->join('specification_types', 'specifications.specification_type_id', '=', 'specification_types.specification_type_id')->orderBy('specification_id', 'asc')->get();
        $specification_types = DB::table('specification_types')->orderBy('nama_jenis_spesifikasi', 'asc')->get();

        return view('admin.spesifikasi')->with('specifications', $specifications)->with('specification_types', $specification_types);
    }

    public function PostTambahSpesifikasi(Request $request) {
        $specification_type_id = $request -> specification_type_id;
        $nama_spesifikasi = $request -> nama_spesifikasi;

        DB::table('specifications')->insert([
            'specification_type_id' => $specification_type_id,
            'nama_spesifikasi' => $nama_spesifikasi,
        ]);

        return redirect('./spesifikasi');
    }

    public function PostEditSpesifikasi(Request $request, $specification_id) {
        $specification_type_id = $request -> specification_type_id;
        $nama_spesifikasi = $request -> nama_spesifikasi;

        DB::table('specifications')->where('specification_id', $specification_id)->update([
            'specification_type_id' => $specification_type_id,
            'nama_spesifikasi' => $nama_spesifikasi,
        ]);

        return redirect('./spesifikasi');
    }
}
