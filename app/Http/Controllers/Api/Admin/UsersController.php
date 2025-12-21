<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with([
                'store:id,user_id,name,address,category_id,government_id,image'
            ])
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'count' => $users->count(),
            'data' => $users
        ]);
    }
}
