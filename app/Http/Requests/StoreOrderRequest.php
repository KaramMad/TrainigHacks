<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
class StoreOrderRequest extends FormRequest
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
            'products'=>'required|array',
            'products.*.product_id'=>'required|integer|min:1|exists:products,id|bail',
            'products.*.quantity'=>'required|min:1|max:500|bail',
            'products.*'=>function ($attribute, $value, $fail) {
                $id = $value['product_id'];
                $product = Product::find($id);
                if ($product === null) {
                    return $fail('Product not found');
                }
                if ($product->stock < (int)$value['quantity']) {
                    return $fail("The specified quantity for product with id {$id} is less than the available stock");
                }
            }
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
