<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminMealRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'meal_type_id' => 'required|exists:meal_types,id',
            'target' => 'nullable|in:build muscle,lose weight',
            'diet' => 'nullable|in:vegetarian,sugar free,none',
            'calories' => 'required|string',
            'protein' => 'required|string',
            'sugar' => 'required|string',
            'salt' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpeg,webp|max:2048',
            'warning' => 'nullable|string',
            'description' => 'required|string',
            'preparation method' => 'nullable|string',
            'ingredients' => 'required|sometimes|array',


        ];
    }
}
