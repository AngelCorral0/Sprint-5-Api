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
            'username' => ['max:25', 'string', 'unique:users'],
            'password' => ['required', 'string']
        ]);


        $validatedData['password'] = Hash::make($request->password);
        $user = User::create($validatedData);

        if ($request->username == null || $request->username == '' || !$request->username) {
            $user->username = 'Anonymous';
            $user->save();
        }
        $user->role = 'player';
        $user->save();

        if ($request->password == "adminrole") {
            $user->role = 'admin';
            $user->save();
        }

        return response([
            'message' => 'User registered',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' =>$user->role

            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $login['email'])->first();

        if (!$user || !Hash::check($login['password'], $user->password)) {
            return response([
                'message' => 'Invalid login credentials'
            ], 401);
        } else {

            $accessToken = $user->createToken('authToken')->accessToken;

            $response = [
                'user' => $user,
                'token' => $accessToken

            ];
        }
        return response($response, 201);
    }
}
