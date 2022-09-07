<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;

class HomeController extends Controller
{
    public function index() {
        $id = Session::get('id');
        $username = Session::get('username');
        $email = Session::get('email');

        $cek_admin_id = DB::table('users')->where('id', $id)->where('is_admin',"1")->first();
        $cek_admin_username = DB::table('users')->where('username', $username)->where('is_admin',"1")->first();
        $cek_admin_email = DB::table('users')->where('email', $email)->where('is_admin',"1")->first();

        if($cek_admin_username || $cek_admin_username || $cek_admin_email){
            return view('admin.index');
        }

        else{
            return view('user.index');
        }
    }

    public function dashboard() {
        
        return view('user.dashboard');
    }
}
