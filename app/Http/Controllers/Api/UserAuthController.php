<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserAuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
            }
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;
            return response()->json([
            'token' =>$token,
            'user'=> new UserResource(Auth::user()),
            ]);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
        'message' => 'Successfully logged out'
        ]);
    }
}
