<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;

class AutentikasiController extends Controller
{
    public function PostRegister(Request $request)
    {
        $request -> validate([
            'username' => 'required|unique:users',    
            'email' => 'required|email|unique:users',
            'password' => 'required',    

            'name' => 'required',    
            'no_hp' => 'required',    
            'birthday' => 'required',    
            'gender' => 'required',    
        ]);

        $data_users = $request->only(['username', 'password', 'email']);
        $data_profiles = $request->only(['name', 'no_hp', 'birthday', 'gender']);
        
        $username = $request->username;
        $email = $request->email;
        $password = $request->password;

        $users  = User::create($data_users);
        $user = User::where('username', $data_users['username'])->pluck('id');
        $id_user = $user[0];
        $data_profiles += ['user_id' => $id_user];

        $profiles  = Profile::create($data_profiles);
        if(Auth::attempt(['username' => $username, 'password' => $password]) || Auth::attempt(['email' => $email, 'password' => $password])){
            $user = Auth::user();
            return redirect()->back();
        }
        
        else{
            return redirect()->back()->with('error', '');
        }    
         
        return redirect('./')->withSuccess('Great! You have Successfully loggedin');
    }

    public function PostLogin(Request $request){

        request()->validate(
            [
                'username_email' => 'required',
                'password' => 'required',
            ]);

        $username_email = $request->username_email;
        $password = $request->password;

        if(Auth::attempt(['username' => $username_email, 'password' => $password]) || Auth::attempt(['email' => $username_email, 'password' => $password])){
            $user = Auth::user();
            return redirect('./');
        }
        
        else{
            return redirect('./')->with('error', '');
        }          
    }

    public function Logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('./');
    }
}
