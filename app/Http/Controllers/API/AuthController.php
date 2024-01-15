<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request) {
        try {
            $request->merge([
                'password' => Hash::make($request->password),
            ]);

            $user = User::create($request->all());
            $token = $user->createToken('access_token')->plainTextToken;
            $user->access_token = $token;
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'user' => $user,
                'message' => 'Register successfully',
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request) {
        try {
            $user = User::where('email', $request->email)->first();
                if($user && Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('access_token')->plainTextToken;
                    $user->access_token = $token;
                        return response()->json([
                        'status' => JsonResponse::HTTP_OK,
                        'data' => $user,
                        'message' => 'login successfully'
                    ], JsonResponse::HTTP_OK);
                }
            return response()->json([
                'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'Invalid credentials',
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'logout successfully',
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
