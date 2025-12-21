<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Government;
use Illuminate\Http\Request;

class UserGovermentsController extends Controller
{
    public function index()
    {
        try {
            $governments = Government::select('id', 'name', 'image')
                ->orderBy('name')
                ->get();

            return response()->json([
                'status' => true,
                'count' => $governments->count(),
                'data' => $governments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch governments',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
