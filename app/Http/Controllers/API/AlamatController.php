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
}   
?>