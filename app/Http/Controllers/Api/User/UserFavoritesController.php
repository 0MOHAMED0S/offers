<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserFavoritesController extends Controller
{
     public function toggle($offer_id)
    {
        $user = auth()->user();

        $validator = Validator::make(
            ['offer_id' => $offer_id],
            ['offer_id' => 'required|exists:offers,id']
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $favorite = Favorite::where('user_id', $user->id)
            ->where('offer_id', $offer_id)
            ->first();

        // Remove
        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'status' => true,
                'message' => 'Removed from favorites',
                'is_favorite' => false
            ]);
        }

        // Add
        Favorite::create([
            'user_id' => $user->id,
            'offer_id' => $offer_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Added to favorites',
            'is_favorite' => true
        ]);
    }
}
