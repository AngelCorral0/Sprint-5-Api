<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    public function register(Request $request)
    {
       $request->validate([
            'email' => ['required', 'unique:users', 'string', 'email'],
            'username' => ['max:25', 'string'],
            'password' => ['required', 'string']
        ]);

            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            if(!$request->username){
                $user = 'Anonymous';
            }else{
                $user->username = $request->usermane;
            }
            $request->admin_password == "Admin123456" ? $user->assignRole('admin') : $user->assignRole('user');
            $user->save();
            $response = [
                'user' => $user
            ];
        return response($response, 201);
    }

    public function login(Request $request)
    {
        $login= $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
       
        $user = User::where('email', $login['email'])->first();
        
        if(!Auth::attempt($login)){
            return response(['message' =>'Invalid login credentials'],401);
        }else{
            $accessToken = $user->createToken('authToken')->accessToken;
        }
        
        return response(['user'=> Auth::user(), 'access_token'=> $accessToken]);
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
        return response()->json($user);
    }
}
