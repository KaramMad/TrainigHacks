<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminMealRequest extends FormRequest
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
            'name' => 'required|string',
            'meal_type_id' => 'required|exists:meal_types,id',
            'target' => 'nullable|in:build muscle,lose weight',
            'diet' => 'nullable|in:vegetarian,sugar free,none',
            'calories' => 'required|string',
            'protein' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpeg,webp|max:2048',
            'warning' => 'nullable|string',
            'description' => 'required|string',
        ];
    }
}
