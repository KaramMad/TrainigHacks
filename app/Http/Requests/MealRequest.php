<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest
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
            'type' => 'required|string|in:breakfast,lunch,dinner,snack',
            'target' => 'required|string|in:build muscle,lose weight',
            'calories' => 'required|numeric|max:1000',
            'protein' => 'required|numeric|max:50',
            'image' => 'nullable|image|mimes:png,jpeg,webp|max:2048',
            'description' => 'required|string',
            'day_id' => 'required|exists:training_days,id',
        ];
    }
}
