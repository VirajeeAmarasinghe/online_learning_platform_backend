<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['user' => $user, 'access_token' => $accessToken]);
    }
    public function login(Request $request)
{
    $loginData = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($loginData)) {
        return response(['message' => 'Invalid credentials'], 401);
    }

    $accessToken = Auth::user()->createToken('authToken')->accessToken;

    return response(['user' => auth()->user(), 'access_token' => $accessToken->token]);
}


    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->token()->revoke();
        }

        return response()->json(['message' => 'Successfully logged out']);
    }
}
