<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Session;

class HomeController extends Controller
{
    public function index() {
        if(Auth::check()){
            $id = Auth::user()->id;
            $cek_admin_id = DB::table('users')->where('id', $id)->where('is_admin', 1)->first();
        }
        
        if(isset($cek_admin_id)){
            return view('admin.index');
        }
 
        if(Session::get('toko')){
            return redirect('./toko');
        }

        else{
            $carousels = DB::table('carousels')->orderBy('id', 'desc')->get();

            $products = DB::table('products')->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->orderBy('product_id', 'desc')->paginate(13);

            $cek_http = DB::table('carousels')->where('link_carousel', 'like', 'https://'."%")->orwhere('link_carousel', 'like', 'http://'."%")->first();
            $cek_www = DB::table('carousels')->where('link_carousel', 'like', 'www.'."%")->first();

            return view('user.index')->with('products', $products)->with('carousels', $carousels)->with('cek_http', $cek_http)->with('cek_www', $cek_www);
        }
    }

    public function dashboard() {
        if(Session::get('toko')){
            return redirect('./toko');
        }
        
        if(Auth::user()){
            return view('user.dashboard');
        }
        
        else{
            return redirect('./');
        }
    }
}
