<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function editUsername(Request $request, $id)
    {
        $userAuth = auth()->user()->id;
      
        if ($userAuth == $id) {
            $user = User::find($id);
            
            if (!$user) {
                return response([
                    'message' => 'User not found'
                ], 404);
            }
            //dd($request);
            $request->validate([
                'username' => 'required|max:25',
            ]);
            
            $user->username = $request->username;
            $user->save();
            
            
            return response([
                'username' => $user->username,
                'message' => 'Username changed successfully'
            ], 200);
        } else {
            return response(['message' => 'Unauthorized'], 401);
        }
    }


    public function showAllUsers()
    {
        if (auth()->user()->role == 'admin') {
            $users = User::all();
            return response()->json($users);
        } else {
            return response([
                'message' => 'unauthorized'
            ], 401);
        }
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role == 'admin') {
            $user->delete();
            return response([
                'message' => 'User deleted successfully'
            ], 200);
        }

        return response([
            'message' => 'Unauthorized'
        ], 401);
    }
}
