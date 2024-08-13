<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
class StoreProductRequest extends FormRequest
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
            'name'=>'required|string',
            'description'=>'required|string',
            'brand'=>'required|string',
            'price'=>'required|string',
            'image'=>'required|image|mimes:png,jpg',
            'weight'=>'nullable|string',
            'measuring_unit'=>'nullable|string',
            'protein'=>'nullable|string',
            'creatine'=>'nullable|string',
            'expiration_date'=>'nullable',
            'category_id'=>'required|array',
            'color_id'=>'nullable|array',
            'color_id.*.id'=>'exists:color_products,id',
            'size_id'=>'nullable|array',
            'size_id.*.id'=>'exists:product_sizes,id',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        return throw new ValidationException($validator, $this->response($validator));
    }
    protected function response($validator)
    {
        return response()->json([
            'status' => false,
            'message' => 'validation failed',
            'errors' => $validator->errors()
        ], 422);
    }
}
