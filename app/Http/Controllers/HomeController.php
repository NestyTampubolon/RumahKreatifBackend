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
            
            $count_products = DB::table('products')->select(DB::raw('COUNT(*) as count_products'))->first();

            $products = DB::table('products')->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->orderBy('product_id', 'desc')->paginate(10);
            
            $product_images = DB::table('product_images')->orderBy('product_image_id', 'asc')->get();

            $cek_http = DB::table('carousels')->where('link_carousel', 'like', 'https://'."%")->orwhere('link_carousel', 'like', 'http://'."%")->first();
            $cek_www = DB::table('carousels')->where('link_carousel', 'like', 'www.'."%")->first();

            return view('user.index')->with('products', $products)->with('product_images', $product_images)->with('carousels', $carousels)->with('cek_http', $cek_http)
            ->with('cek_www', $cek_www)->with('count_products', $count_products);
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
