<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('user');
            return response()->json(['message' => 'Login successful', 'token' => $token->plainTextToken]);
        }
        return response()->json(['message' => 'Login failed'], 401);
    }
}
