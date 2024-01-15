<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Dish;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\DishRequest;
use Illuminate\Http\JsonResponse;

class DishController extends Controller
{
    public function index() {
        try {
            $dishes = Dish::with('user')->latest()->paginate(request()->per_page, ['*'], 'page', request()->page);
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'dishes' => $dishes,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(DishRequest $request) {
        try {
            $dish = Dish::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'user_id' => $request->input('user_id'),
            ]);

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Dish added successfully',
                'dish' => $dish
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id) {
        try {
            $dish = Dish::find($id);
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'dish' => $dish,
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id) {
        try {
            $dish = Dish::find($id);
            $dish->delete();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => "Dish Deleted Successfully",
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
