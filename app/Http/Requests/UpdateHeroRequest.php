<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateHeroRequest extends FormRequest
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
            'full_name' => 'nullable|string',
            'image' => 'nullable|max:10248',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (!array_key_exists('full_name', $this->all()) && !array_key_exists('image', $this->all())) {
                $validator->errors()->add('full_name', 'שם הרחט"ל או תמונה חייבים להיות נוכחים.');
                $validator->errors()->add('image', 'שם הרחט"ל או תמונה חייבים להיות נוכחים.');
            }

            if ($this->hasFile('image')) {
                $file = $this->file('image');
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, ['jpeg', 'jpg', 'png', 'jfif'])) {
                    $validator->errors()->add('image', 'התמונה חייבת להיות מסוג: jpeg, png, jpg, jfif.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'full_name.string' => 'פורמט לא תקין.',
            'image.max' => 'גודל התמונה לא יכול לעלות על 10MB.',
        ];
    }
}
