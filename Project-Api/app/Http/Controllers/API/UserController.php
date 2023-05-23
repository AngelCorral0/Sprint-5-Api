<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    
    public function editUsername(Request $request,$id)
    {
        $userAuth= Auth::user()->id;
        if($userAuth == $id)
        {
            $user =User::find($id);

            $request->validate([
                'username'=>'required|max:25',
            ]);
        }else if(!User::find($id))
        {
            return response([
                'message'=>'Username not fount'
            ],404);
        }else
        {
            return response(['message'=>'Unauthorized'],401);
        }    
        
        $user->update($request->all());

      return response([
        'message' => 'Username changed successfully'],200);
    }

   
    public function showAllUsers()
    {
        if(Auth::user()->role=='admin')
        {
            $users = User::all();
            return response()->json($users);
        }
        return response([
            'message'=> 'unauthorized'
        ],401);
    }

        public function destroy(User $user)
    {
        if(Auth::user()->role=='admin')
        {
            $user->delete();
            return response([
                'message'=>'User deleted successfully'],200);
        }
       
        return response([
            'message'=>'Unauthorized'
        ],401);
    }
}
