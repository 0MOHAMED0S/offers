<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    /* =========================
        GET ALL OFFERS (STORE)
    ========================== */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->store) {
            return response()->json([
                'status' => false,
                'message' => 'Store profile not found'
            ], 403);
        }

        return response()->json([
            'status' => true,
            'data' => Offer::where('store_id', $user->store->id)->get()
        ]);
    }

    /* =========================
        CREATE OFFER
    ========================== */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->store) {
            return response()->json([
                'status' => false,
                'message' => 'This user does not have a store profile'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:offers,name,NULL,id,store_id,' . $user->store->id,
            'description' => 'required|string|max:2000',
            'price_before' => 'required|numeric|gt:price_after',
            'price_after' => 'required|numeric|lt:price_before',
            'status' => 'nullable|in:active,not_active,not_available',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $offer = Offer::create([
                'store_id' => $user->store->id,
                'name' => $request->name,
                'description' => $request->description,
                'price_before' => $request->price_before,
                'price_after' => $request->price_after,
                'status' => $request->status ?? 'active',
                'image' => $request->file('image')->store('offers', 'public'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Offer created successfully',
                'data' => $offer
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /* =========================
        SHOW OFFER
    ========================== */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $offer = Offer::where('id', $id)
            ->where('store_id', $user->store->id ?? null)
            ->first();

        if (!$offer) {
            return response()->json([
                'status' => false,
                'message' => 'Offer not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $offer
        ]);
    }

    /* =========================
        UPDATE OFFER
    ========================== */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->store) {
            return response()->json([
                'status' => false,
                'message' => 'Store profile not found'
            ], 403);
        }

        $offer = Offer::where('id', $id)
            ->where('store_id', $user->store->id)
            ->first();

        if (!$offer) {
            return response()->json([
                'status' => false,
                'message' => 'Offer not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255|unique:offers,name,' . $offer->id . ',id,store_id,' . $user->store->id,
            'description' => 'nullable|string|max:2000',
            'price_before' => 'nullable|numeric|gt:price_after',
            'price_after' => 'nullable|numeric',
            'status' => 'nullable|in:active,not_active,not_available',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->filled('name')) {
                $offer->name = $request->name;
            }

            if ($request->filled('description')) {
                $offer->description = $request->description;
            }

            if ($request->filled('price_before')) {
                $offer->price_before = $request->price_before;
            }

            if ($request->filled('price_after')) {
                $offer->price_after = $request->price_after;
            }

            if ($request->filled('status')) {
                $offer->status = $request->status;
            }

            if ($request->hasFile('image')) {
                if ($offer->image && Storage::disk('public')->exists($offer->image)) {
                    Storage::disk('public')->delete($offer->image);
                }

                $offer->image = $request->file('image')->store('offers', 'public');
            }

            $offer->save();

            return response()->json([
                'status' => true,
                'message' => 'Offer updated successfully',
                'data' => $offer
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /* =========================
        DELETE OFFER
    ========================== */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $offer = Offer::where('id', $id)
            ->where('store_id', $user->store->id ?? null)
            ->first();

        if (!$offer) {
            return response()->json([
                'status' => false,
                'message' => 'Offer not found'
            ], 404);
        }

        if ($offer->image && Storage::disk('public')->exists($offer->image)) {
            Storage::disk('public')->delete($offer->image);
        }

        $offer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Offer deleted successfully'
        ]);
    }
}
