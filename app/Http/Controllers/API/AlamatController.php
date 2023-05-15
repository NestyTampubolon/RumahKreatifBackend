<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlamatController extends Controller{
    // public function AlamatPenggunas(Request $request)
    // {
    //     $alamat = DB::table('user_address')
    //         ->where('user_address.user_id', $request->user_id)
    //         ->join('users', 'user_address.user_id', '=', 'users.id')
    //         ->get();
    //     $alamats = DB::table('user_address')->where('user_id', $request->user_id)->get();
    //     return response()->json([
    //         'alamat' => $alamat,
    //         'alamats' => $alamats,
    //     ]);

        
    // }    
    public function AlamatPengguna(Request $request)
    {
        $alamat = DB::table('user_address')->select('*')->where('user_id', '=', $request->user_id)->get();
        return response()->json([
            'alamat' => $alamat,
        ]);
    }

    public function TambahAlamat(Request $request)
    {
        $user_id = DB::table('user_address')->select('*')->where('user_id', '=', $request->user_id)->get();
        $user_id = $request->user_id;
        $province_id = $request->province_id;
        $province_name = $request->province_name;
        $city_id = $request->city_id;
        $city_name = $request->city_name;
        $subdistrict_id = $request->subdistrict_id;
        $subdistrict_name = $request->subdistrict_name;
        $user_street_address = $request->user_street_address;

        $user_address_id = DB::table('user_address')->insertGetId([
            'user_id' => $user_id,
            'province_id' => $province_id,
            'province_name' => $province_name,
            'city_id' => $city_id,
            'city_name' => $city_name,
            'subdistrict_id' => $subdistrict_id,
            'subdistrict_name' => $subdistrict_name,
            'user_street_address' => $user_street_address,
            'is_deleted' => 0,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Alamat berhasil ditambahkan',
            'data' => $user_address_id, 
        ], 200);
    }

    public function HapusAlamat(Request $request)
    {

        if (DB::table('user_address')->where('user_address_id', $request->user_address_id)->delete()) {
            return response()->json(
                200
            );
        }
    }


}   
?>