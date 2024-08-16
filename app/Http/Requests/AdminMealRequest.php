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
            'name' => 'required|string|max:16',
            'target' => 'required|in:build muscle,lose weight,keep fit',
            'type' => 'nullable|in:vegetarian,sugar free,none',
            'calories' => 'required|string',
            'protein' => 'required|string',
            'sugar' => 'required|string',
            'salt' => 'required|string',
            'image' => 'required|image|mimes:png,jpeg,webp|',
            'warning' => 'nullable|string',
            'description' => 'required|string',
            'preparation_method' => 'nullable|string',
            'ingredients' => 'required|sometimes|array',
            'categoryName' => 'required|string|in:breakfast,lunch,dinner,snack',


        ];
    }
}
