<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ProfilController extends Controller
{
    public function profil() {
        $id = Auth::user()->id;
    
        $profile = DB::table('profiles')->join('users', 'profiles.user_id', '=', 'users.id')->where('profiles.user_id', $id)->first();

        return view('user.profil')->with('profile', $profile);
    }

    public function edit_profil() {
        $id = Auth::user()->id;
    
        $profile = DB::table('profiles')->join('users', 'profiles.user_id', '=', 'users.id')->where('profiles.user_id', $id)->first();

        return view('user.edit_profil')->with('profile', $profile);
    }

    public function PostEditProfil(Request $request) {
        $id = Auth::user()->id;

        $name = $request -> name;
        $gender = $request -> gender;
        $birthday = $request -> birthday;
        $no_hp = $request -> no_hp;

        DB::table('profiles')->where('user_id', $id)->update([
            'name' => $name,
            'gender' => $gender,
            'birthday' => $birthday,
            'no_hp' => $no_hp,
        ]);

        return redirect('./profil');
    }
}
