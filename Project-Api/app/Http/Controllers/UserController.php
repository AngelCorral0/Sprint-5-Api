<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function index()
    {
        $user = User::all();
        return $user;
    }

    public function editUsername(Request $request,$id)
    {
        $userAuth= Auth::user()->id;
        if($userAuth == $id)
        {
            $user =User::find($id);

            $request->validate([
                'username'=>'reuquired|max:25',
            ]);
        }else if(!User::fin($id))
        {
            return response([
                'message'=>'Username not fount'
            ],404);
        }else
        {
            return response(['message'=>'Unauthorized'],401);
        }    
        $user->update($request->all());

      $data= [
        'message' => 'Username changed successfully',
        'username' =>$user
      ];

      return response()->json($data);
    }

   
    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        $data = [
            'message'=>'User deleted successfully',
            'username' => $user
        ];
        return response()->json($user);
    }
}
