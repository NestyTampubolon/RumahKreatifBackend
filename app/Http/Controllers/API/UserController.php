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
        // Get the authenticated user's ID
        $userId = $request->user()->id;

        // Retrieve the user's data from the database
        $user = User::find($userId);

        // Return the user's data as a JSON response
        return response()->json([
            'user' => $user
        ]);
    }

}
