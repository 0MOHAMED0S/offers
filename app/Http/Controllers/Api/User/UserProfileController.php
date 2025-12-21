<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'avatar' => $user->avatar,
            ];

            if ($user->role === 'store' && $user->store) {
                $response['store'] = [
                    'id' => $user->store->id,
                    'name' => $user->store->name,
                    'address' => $user->store->address,
                    'image' => $user->store->image,
                    'category' => [
                        'id' => $user->store->category->id,
                        'name' => $user->store->category->name,
                    ],
                    'government' => [
                        'id' => $user->store->government->id,
                        'name' => $user->store->government->name,
                    ],
                ];
            }

            return response()->json([
                'status' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
