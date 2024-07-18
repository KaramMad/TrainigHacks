<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
class StoreRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating'=>'required|integer|min:1|max:5',
            'rateable_id'=>'required|integer',

        ];
    }
    protected function failedValidation(Validator $validator){
        return throw new ValidationException($validator,$this->response($validator));
    }
    protected function response($validator){
        return response()->json([
            'status' => false,
            'message' => 'validation failed',
            'errors' => $validator->errors()
        ], 422);
    }
}
