<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserStoresController extends Controller
{
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
            $stores = Store::select(
                    'id',
                    'name',
                    'image',
                    'address',
                    'category_id',
                    'government_id'
                )
                ->where('category_id', $category_id)
                ->where('government_id', $government_id)
                ->with([
                    'category:id,name',
                    'government:id,name'
                ])
                ->orderBy('name')
                ->get();

            return response()->json([
                'status' => true,
                'count' => $stores->count(),
                'data' => $stores
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch stores',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
