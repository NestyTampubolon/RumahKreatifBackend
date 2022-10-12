<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class AlamatController extends Controller
{
    public function alamat(Request $request) {
        if(Session::get('toko')){
            return view('user.toko.alamat');
        }

        else{
            $user_id = Auth::user()->id;

            $cek_admin_id = DB::table('users')->where('id', $user_id)->where('is_admin', 1)->first();

            if($cek_admin_id){
                
            }

            else{
                $user_id = Auth::user()->id;
    
                return view('user.alamat');
            }
        }
    }
    
    public function ambil_lokasi(Request $request) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/" . $request->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array("key: 41df939eff72c9b050a81d89b4be72ba"),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        return response()->json($response);
    }

    public function PostAlamat(Request $request) {
        $province_id = $request -> province;
        $city_id = $request -> city;
        $subdistrict_id = $request -> subdistrict;
        $street_address = $request -> street_address;

        if(Session::get('toko')){
            $toko = Session::get('toko');


            DB::table('merchant_address')->insert([
                'merchant_id' => $toko,
                'province_id' => $province_id,
                'city_id' => $city_id,
                'subdistrict_id' => $subdistrict_id,
                'merchant_street_address' => $street_address,
            ]);
            
        }

        else{
            $user_id = Auth::user()->id;

            $cek_admin_id = DB::table('users')->where('id', $user_id)->where('is_admin', 1)->first();

            if($cek_admin_id){
                
            }

            else{
                $id = Auth::user()->id;

                DB::table('user_address')->insert([
                    'user_id' => $id,
                    'province_id' => $province_id,
                    'city_id' => $city_id,
                    'subdistrict_id' => $subdistrict_id,
                    'user_street_address' => $street_address,
                ]);
            }
        }

        return redirect('./daftar_alamat');
    }

    public function daftar_alamat() {
        if(Session::get('toko')){
            $toko = Session::get('toko');
            $merchant_address = DB::table('merchant_address')->where('merchant_id', $toko)->first();

            $cek_merchant_address = DB::table('merchant_address')->where('merchant_id', $toko)->count();

            if($cek_merchant_address == 0){
                return redirect('./alamat');
            }

            else{
                return view('user.toko.daftar_alamat')->with('merchant_address', $merchant_address);
            }
        }

        else{
            $user_id = Auth::user()->id;

            $cek_admin_id = DB::table('users')->where('id', $user_id)->where('is_admin', 1)->first();

            if($cek_admin_id){
                
            }

            else{
                $user_id = Auth::user()->id;
                $user_address = DB::table('user_address')->where('user_id', $user_id)->orderBy('user_address_id', 'asc')->get();
    
                return view('user.daftar_alamat')->with('user_address', $user_address);
            }
        }
    }

    public function HapusAlamat($address_id)
    {
        if(Session::get('toko')){
            $toko = Session::get('toko');
            
            DB::table('merchant_address')->where('merchant_address_id', $address_id)->delete();
                
            return redirect('./daftar_alamat');
            
        }

        else{
            $user_id = Auth::user()->id;

            $cek_admin_id = DB::table('users')->where('id', $user_id)->where('is_admin', 1)->first();

            if($cek_admin_id){
                
            }

            else{
                $user_id = Auth::user()->id;
                
                DB::table('user_address')->where('user_address_id', $user_address_id)->delete();
                
                return redirect('./daftar_alamat');
            }
        }
    }
}
