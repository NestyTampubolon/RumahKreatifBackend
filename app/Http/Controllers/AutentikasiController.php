<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Hash;

class AutentikasiController extends Controller
{
    public function PostRegister(Request $request)
    {
        $username = $request -> username;
        $email = $request -> email;
        $password = $request -> password;

        $name = $request -> name;
        $no_hp = $request -> no_hp;
        $birthday = $request -> birthday;
        $gender = $request -> gender;

        $cek_username = DB::table('users')->where('username',$username)->first();
        $cek_email = DB::table('users')->where('email',$email)->first();

        if($cek_username && $cek_email){
            return redirect('./');
        }

        if($cek_username){
            return redirect('./');
        }

        if($cek_email){
            return redirect('./');
        }

        if(!$cek_username && !$cek_email){
            DB::table('users')->insert([
                'username' => $username,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $user_id = DB::table('users')->orderBy('id', 'desc')->first();

            DB::table('profiles')->insert([
                'user_id' => $user_id->id,
                'name' => $name,
                'no_hp' => $no_hp,
                'birthday' => $birthday,
                'gender' => $gender,
            ]);

            Session::put('username',$username);
            Session::put('email',$email);

            return redirect('./');
        }
    }

    public function PostLogin(Request $request){

        $username_email = $request->username_email;
        $password = $request->password;

        $cek_username = DB::table('users')->where('username',$username_email)->first();
        $cek_email = DB::table('users')->where('email',$username_email)->first();

        $cek_admin = DB::table('users')->where('is_admin',"1")->first();

        if($cek_username){
            $cek_login = DB::table('users')->where('username',$username_email)->where('password', Hash::check('plain-text', $password))->first();
        }

        if($cek_email){
            $cek_login = DB::table('users')->where('email',$username_email)->where('password', Hash::check('plain-text', $password))->first();
        }

        if($cek_login){
            Session::put('username',$cek_login->username);
            Session::put('email',$cek_login->email);
            return redirect('./');
        }

        if($cek_login ){
            Session::put('username',$cek_login->username);
            Session::put('email',$cek_login->email);
            return redirect('./');
        }

        else{
            return redirect('./')->with('alert','');
        }
    }

    public function Logout()
    {
        Session::flush();
        return redirect('./');
    }
}
