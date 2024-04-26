<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserInfo extends FormRequest
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
            'gender' => 'required',
            'target' => 'required',
            'weight' => 'required',
            'tall' => 'required',
            'preferred_time' => 'required',
            'focus_area' => 'required',
            'training_days' => 'required|array',
            'diseases' => 'required'
        ];
    }
}
