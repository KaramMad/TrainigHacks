<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExercisesTypeRequest extends FormRequest
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
            'exercise_type_id' => 'required|min:1|exists:exercise_types,id',
            'exercise_name' => 'required|string|',
            'description' => 'required|string|',
            'calories' => 'required|numeric|max:130',
            'time' => 'required|',
            'reps' => 'required|numeric|max:12|',
            'gif' => 'required|image|mimes:gif|',
            'video_link' => 'required|string',
            'target' => 'required|string|in:lose_weight,build_muscle,keep_fit',
            'level' => 'required|in:beginner,intermediate,advanced',
            'gender' => 'required|string|in:male,female',
            'choose' => 'required|in:equipment,no_equipment',
            'focus_area' => 'required|array',
            'muscle' => 'required|array',
            'muscle.*.id' => 'exists:muscles,id',
            'category' => 'required|array',
            'diseases' => 'required|in:heart,none,knee,breath',
            'private' => 'required|boolean',
        ];
    }
}
