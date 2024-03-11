<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {

            $user = User::where('email', $credentials['email'])->first();
            if (!$user) {
                return response()->json([
                    'message' => 'This email is incorrect'
                ], 401);
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'message' => 'Wrong Password'
                ], 401);
            }

            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
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
