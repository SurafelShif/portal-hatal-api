<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateRahtalRequest extends FormRequest
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
            'full_name' => 'nullable|string|min:2',
            'image' => 'nullable|file|max:2048',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('image')) {
                $file = $this->file('image');
                $extension = $file->getClientOriginalExtension();

                if (!in_array($extension, ['jpeg', 'jpg', 'png'])) {
                    $validator->errors()->add('image', 'התמונה חייבת להיות מסוג: jpeg, png, jpg.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'full_name.string' => 'שם הרחט"ל חייב להיות מחרוזת.',
            'full_name.min' => 'שם הרחט"ל צריך להכיל לפחות שני תווים.',
            'image.file' => 'התמונה חייבת להיות קובץ.',
            'image.max' => 'גודל התמונה לא יכול לעלות על 2MB.',
        ];
    }
}
