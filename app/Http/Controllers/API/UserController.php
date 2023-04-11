<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    //
    // public function index()
    // {
    //     $users = DB::table('users')
    //         ->join('profiles', 'users.id', '=', 'profiles.user_id')->get();
            

    //     return response()->json(
    //         $users
    //     );
    // }

    public function index(Request $request)
    {
        $user = $request->user();
        return response()->json($user,200);
    }

}
