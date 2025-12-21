<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
public function updateUserToStore(Request $request, $user_id)
{
    try {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->role !== 'user' && $user->role !== 'store') {
            return response()->json([
                'status' => false,
                'message' => 'Only users with role "user" or "store" can be updated'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'store_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'government_id' => 'required|exists:governments,id',
            'image' => $user->role === 'user'
                        ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                        : 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'store_name.required' => 'Store name is required.',
            'address.required' => 'Address is required.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'government_id.required' => 'Government is required.',
            'government_id.exists' => 'Selected government does not exist.',
            'image.required' => 'Store image is required for new stores.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Allowed image types: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Image size should not exceed 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        if ($user->role === 'user') {
            $user->role = 'store';
            $user->save();
        }

        $store = Store::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $request->store_name,
                'address' => $request->address,
                'category_id' => $request->category_id,
                'government_id' => $request->government_id,
                'image' => $request->hasFile('image')
                            ? $request->file('image')->store('stores', 'public')
                            : ($user->store->image ?? null),
            ]
        );

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => $user->role === 'store' ? 'Store updated successfully' : 'User converted to store successfully',
            'data' => [
                'user' => $user,
                'store' => $store
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Failed to update user/store',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
