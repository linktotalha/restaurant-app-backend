<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $validator->errors()
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY,);

        throw new ValidationException($validator, $response);
    }
}
