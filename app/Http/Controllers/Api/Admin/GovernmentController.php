<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Government;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GovernmentController extends Controller
{
    // List all governments
    public function index()
    {
        try {
            $governments = Government::all();
            return response()->json([
                'status' => true,
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

    // Create a new government
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:governments,name|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'name.required' => 'The government name is required.',
                'name.unique' => 'This government already exists.',
                'name.max' => 'The government name is too long.',
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

            $imagePath = $request->hasFile('image') ? $request->file('image')->store('governments', 'public') : null;

            $government = Government::create([
                'name' => $request->name,
                'image' => $imagePath
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Government created successfully',
                'data' => $government
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create government',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show a single government
    public function show(Government $government)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $government
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch government',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update a government
    public function update(Request $request, $id)
    {
        try {
            $government = Government::find($id);

            if (!$government) {
                return response()->json([
                    'status' => false,
                    'message' => 'Government not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|unique:governments,name,' . $government->id . '|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'name.unique' => 'This government name is already taken.',
                'name.max' => 'The government name is too long.',
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

            if ($request->filled('name')) {
                $government->name = $request->name;
            }

            if ($request->hasFile('image')) {
                if ($government->image && Storage::disk('public')->exists($government->image)) {
                    Storage::disk('public')->delete($government->image);
                }
                $government->image = $request->file('image')->store('governments', 'public');
            }

            $government->save();

            return response()->json([
                'status' => true,
                'message' => 'Government updated successfully',
                'data' => $government
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update government',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    // Delete a government
    public function destroy($id)
    {
        try {
            $government = Government::find($id);

            if (!$government) {
                return response()->json([
                    'status' => false,
                    'message' => 'Government not found'
                ], 404);
            }

            if ($government->image && Storage::disk('public')->exists($government->image)) {
                Storage::disk('public')->delete($government->image);
            }

            $government->delete();

            return response()->json([
                'status' => true,
                'message' => 'Government deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete government',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
