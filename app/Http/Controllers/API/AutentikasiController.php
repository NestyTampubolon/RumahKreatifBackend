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

        // $data_users = new User();
        // $data_users->username = $request->username;
        // $data_users->password = Hash::make($request->password);
        // $data_users->email = $request->email;


        // if ($data_users->save()) {
        //     $data_profiles = new Profile();
        //     $data_profiles->user_id = $data_users->id;
        //     $data_profiles->name = $request->name;
        //     $data_profiles->no_hp = $request->no_hp;
        //     $data_profiles->birthday = $request->birthday;
        //     $data_profiles->gender = $request->gender;

        //     $token = $data_users->createToken('MyApp')->plainTextToken;

        //     if ($data_profiles->save()) {
        //         return response()->json([
        //             'token' => $token,
        //         ],200);
        //     }
        // }

        $data_users = $request->only(['username', 'password', 'email']);
        $data_profiles = $request->only(['name', 'no_hp', 'birthday', 'gender']);

        $username = $request->username;
        $email = $request->email;
        $password = $request->password;

        $users = User::create($data_users);
        $user = User::where('username', $data_users['username'])->pluck('id');
        $id_user = $user[0];
        $data_profiles += ['user_id' => $id_user];

        $profiles = Profile::create($data_profiles);

        if (Auth::attempt(['username' => $username, 'password' => $password]) || Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken; // Use $user instead of $data_users

            if ($profiles->save()) {
                return response()->json([
                    'token' => $token,
                ], 200);
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

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => $user->password,
            ], 200);
        }

        $token =  $user->createToken('MyApp')->plainTextToken;
        return response()->json([
            'token' => $token,
        ], 200);
        
    }

    public function cekLogin(Request $request)
{
    $validasi = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required',
    ]);

    if ($validasi->fails()) {
        $val = $validasi->errors()->all();
        return response()->json(['message' => $val[0]], 400);
    }

    $user = User::where('username', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Username atau password salah.'], 401);
    }

    // Periksa apakah pengguna adalah admin
    if ($user->is_admin != 1) {
        return response()->json(['message' => 'Hanya admin yang diizinkan untuk login.'], 403);
    }

    // Jika pengguna adalah admin, buat token dan kembalikan respons
    $token = $user->createToken('MyApp')->plainTextToken;
    return response()->json(['token' => $token], 200);
}
}
