<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:16',
            'categoryName' => 'required|string|in:breakfast,lunch,dinner,snack',
            'target' => 'required|string|in:build muscle,lose weight',
            'calories' => 'required|numeric|max:1000',
            'protein' => 'required|numeric|max:50',
            'image' => 'required|image|mimes:png,jpeg,webp|',
            'description' => 'required|string',
            'preparation_method' => 'required|string',
            'day_id' => 'required|exists:training_days,id',
            'ingredients' => 'required|sometimes|array',
        ];
    }
}
