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
            $products = DB::table('products')->orderBy('product_id', 'desc')->join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('merchants', 'products.merchant_id', '=', 'merchants.merchant_id')->get();

            return view('user.index')->with('products', $products);
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
