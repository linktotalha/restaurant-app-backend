<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dish;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function index($id)
    {
        try {
            $dish = Dish::findOrFail($id);
            $reviews = $dish->reviews()->with('user');
            $reviews = $reviews->paginate(10);
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'reviews' =>  $reviews,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $dish = Dish::findOrFail($request->dish_id);
            $review = $dish->reviews()->updateOrCreate(['user_id' => $request->user_id], [
                'rating' => $request->rating,
                'review' => $request->review,
                'rating' => $request->rating,
                'dish_id' => $request->dish_id,
                'user_id' => $request->user_id
            ]);

            if($review->wasRecentlyCreated) {
                $message = "Review added successfully!";
            } else {
                $message = "Review update successfully!";
            }
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' =>  $message,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
