<?php

namespace App\Http\Controllers\Api\App\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string'
        ]);

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->input('access_token'));

            $user = User::where('provider', 'google')
                ->where('provider_id', $googleUser->getId() ?? $googleUser->id)
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->name ?? 'User',
                    'email' => $googleUser->getEmail() ?? $googleUser->email,
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId() ?? $googleUser->id,
                    'password' => Hash::make(Str::random(16)),
                    'avatar' => $googleUser->getAvatar() ?? $googleUser->avatar ?? null,
                    'role' => 'user',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            if ($user->status === 'blocked') {
                return response()->json([
                    'status' => false,
                    'message' => 'Account blocked'
                ], 403);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Google token',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
