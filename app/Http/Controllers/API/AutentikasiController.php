<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class AutentikasiController extends Controller
{
    //
    public function Register(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'no_hp'  => 'required',
            'birthday'  => 'required',
            'gender'  => 'required',
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return response()->json(['message' => $val[0]], 400);
        }

        $data_users = new User();
        $data_users->username = $request->username;
        $data_users->password = Hash::make($request->password);
        $data_users->email = $request->email;

        if ($data_users->save()) {
            $data_profiles = new Profile();
            $data_profiles->user_id = $data_users->id;
            $data_profiles->name = $request->name;
            $data_profiles->no_hp = $request->no_hp;
            $data_profiles->birthday = $request->birthday;
            $data_profiles->gender = $request->gender;

            $token = $data_users->createToken('MyApp')->plainTextToken;

            if ($data_profiles->save()) {
                return response()->json([
                    'token' => $token,
                ],200);
            }
        }
    }

    public function PostLogin(Request $request)
    {

        $validasi = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return response()->json(['message' => $val[0]], 400);
        }

        // $user = User::where('username', $request->username)->first();

        // if ($user) {

        //     if (password_verify($request->password, $user->password)) {
        //         $randomString = Str::random(30);
        //         $users = DB::table('users')
        //         ->where('id', $user->id)
        //         ->join('profiles', 'users.id', '=', 'profiles.user_id')->get();
        //         return response()->json([
        //             'token' => $randomString,
        //             'user' => $users
        //         ]);
        //     }

        // }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username atau password tidak sesuai',
            ], 200);
        }

        $token =  $user->createToken('MyApp')->plainTextToken;
        return response()->json([
            'token' => $token,
        ], 200);
        
    }
}
