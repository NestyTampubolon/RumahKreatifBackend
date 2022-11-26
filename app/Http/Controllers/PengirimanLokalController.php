<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class PengirimanLokalController extends Controller
{
    public function pengiriman_lokal(Request $request) {
        return view('user.toko.pengiriman_lokal');
    }

    public function PostPengirimanLokal(Request $request) {
        $province_id = $request -> province;
        $city_id = $request -> city;
        $subdistrict_id = $request -> subdistrict;
        $biaya_jasa = $request -> biaya_jasa;

        $toko = Session::get('toko');

        DB::table('shipping_locals')->insert([
            'merchant_id' => $toko,
            'shipping_local_province_id' => $province_id,
            'shipping_local_city_id' => $city_id,
            'shipping_local_subdistrict_id' => $subdistrict_id,
            'biaya_jasa' => $biaya_jasa,
        ]);

        return redirect('./daftar_pengiriman_lokal');
    }

    public function daftar_pengiriman_lokal() {
        // set logout saat habis session dan auth
        if(!Auth::check()){
            return redirect("./logout");
        }
        
        if(Session::get('toko')){
            
            $toko = Session::get('toko');

            $shipping_locals = DB::table('shipping_locals')->where('merchant_id', $toko)->get();

            $cek_shipping_locals = DB::table('shipping_locals')->where('merchant_id', $toko)->count();

            // $curl = curl_init();
            
            // foreach($shipping_locals as $shipping_local_detail){
            //     $param = $shipping_local_detail->shipping_local_city_id;
            //     $subdistrict_id = $shipping_local_detail->shipping_local_subdistrict_id;
                
            //     curl_setopt_array($curl, array(
            //         CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=".$param,
            //         CURLOPT_RETURNTRANSFER => true,
            //         CURLOPT_ENCODING => "",
            //         CURLOPT_MAXREDIRS => 10,
            //         CURLOPT_TIMEOUT => 30,
            //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //         CURLOPT_CUSTOMREQUEST => "GET",
            //         CURLOPT_HTTPHEADER => array("key: 41df939eff72c9b050a81d89b4be72ba"),
            //     ));

            //     $response = curl_exec($curl);
            //     $collection = json_decode($response, true);
            //     $filters =  array_filter($collection['rajaongkir']['results'], function($r) use ($subdistrict_id) {
            //         return $r['subdistrict_id'] == $subdistrict_id;
            //     });
                
            //     foreach ($filters as $filter){
            //         $pengiriman_lokal = $filter;
            //     }
                
            //     $err = curl_error($curl);
            //     curl_close($curl);
            // }
            
            

            if($cek_shipping_locals == 0){
                return redirect('./pengiriman_lokal');
            }

            else{
                return view('user.toko.daftar_pengiriman_lokal')->with('shipping_locals', $shipping_locals);
            }
        }
    }

    public function HapusPengirimanLokal($shipping_local_id)
    {
        $toko = Session::get('toko');
        
        DB::table('shipping_locals')->where('shipping_local_id', $shipping_local_id)->delete();
            
        return redirect('./daftar_pengiriman_lokal');
    }

}
