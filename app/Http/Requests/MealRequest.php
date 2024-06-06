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
            'type' => 'required|string|in:breakfast,lunch,dinner,snack',
            'target' => 'required|string|in:build muscle,lose weight',
            'calories' => 'required|numeric|max:1000',
            'protein' => 'required|numeric|max:50',
            'image' => 'nullable|image|mimes:png,jpeg,webp|max:2048',
            'description' => 'required|string',
            'preparation method' => 'nullable|string',
            'day_id' => 'required|exists:training_days,id',
        ];
    }
}
