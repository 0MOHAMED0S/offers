<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserOffersController extends Controller
{
    public function byGovernment(Request $request, $government_id)
    {
        $validator = Validator::make(
            ['government_id' => $government_id],
            ['government_id' => 'required|exists:governments,id']
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $offers = Offer::with([
                'store:id,name,government_id,image,category_id'
            ])
                ->where('status', 'active')
                ->whereHas('store', function ($q) use ($government_id) {
                    $q->where('government_id', $government_id);
                })
                ->latest()
                ->get();

            return response()->json([
                'status' => true,
                'count' => $offers->count(),
                'data' => $offers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch offers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function byCategoryAndGovernment($category_id, $government_id)
    {
        $validator = Validator::make(
            [
                'category_id' => $category_id,
                'government_id' => $government_id
            ],
            [
                'category_id' => 'required|exists:categories,id',
                'government_id' => 'required|exists:governments,id'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $offers = Offer::select(
                'id',
                'name',
                'description',
                'price_before',
                'price_after',
                'status',
                'image',
                'store_id'
            )
                ->where('status', 'active')
                ->whereHas('store', function ($q) use ($category_id, $government_id) {
                    $q->where('category_id', $category_id)
                        ->where('government_id', $government_id);
                })
                ->with([
                    'store:id,name,image,category_id,government_id',
                    'store.category:id,name',
                    'store.government:id,name'
                ])
                ->latest()
                ->get();

            return response()->json([
                'status' => true,
                'count' => $offers->count(),
                'data' => $offers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch offers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
