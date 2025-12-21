<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class UserCategoriesController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::select('id', 'name', 'image')
                ->orderBy('name')
                ->get();

            return response()->json([
                'status' => true,
                'count' => $categories->count(),
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
