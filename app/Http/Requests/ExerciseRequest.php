<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
class ExerciseRequest extends FormRequest
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

            'exercise_name' => 'required|string|',
            'gender' => 'required|string|in:male,female',
            'target' => 'required|string|in:lose_weight,build_muscle,keep_fit',
            'time' => 'required|date_format:h:i',
            'focus_area' => 'required|array',
            'focus_area'=>'',
            'training_days' => 'required|array',
            'diseases' => 'required|in:heart,none,knee,breath',
            'image' => 'nullable|image|mimes:png,jpeg,webp|max:2048',
            'bio'=>'nullable|string|max:49',
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
