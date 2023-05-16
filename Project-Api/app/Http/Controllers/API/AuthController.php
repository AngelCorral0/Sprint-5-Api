<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
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

         if(!$user || !Hash::check($login['password'], $user->password)){
            return response([
                'message' => 'Invalid login credentials'],401);
         }else{
            $accessToken = $user->createToken('authToken')->accessToken;
            $response = [
                'user' => $user,
                'token' => $accessToken
            ];
         }
         return response($response, 201);
       
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logged out'
        ],200);
    }

}
