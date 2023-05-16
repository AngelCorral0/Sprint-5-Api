<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HasApiTokens;

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'unique:users', 'string', 'email'],
            'username' => ['max:25', 'string'],
            'password' => ['required', 'string']
        ]);
        
        if($request->username == null||$request->username ==''){
            $request->merge(['username' => 'Anonymous']); 
         }
    
         $validatedData['password'] = Hash::make($request->password);
        $user = User::create($validatedData);
        $accessToken = $user->createToken('authToken')->accessToken;
        
        
        return response([
            'message' => 'User registered',
        'user' => [
            'username' => $user->username,
            'email'=>$user->email,
            'access_token' => $accessToken
        ],201]);
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
