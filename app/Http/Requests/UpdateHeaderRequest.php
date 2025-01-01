<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHeaderRequest extends FormRequest
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
            'icons' => 'nullable|array|required_without_all:description',
            'description' => 'nullable|string|required_without_all:icons',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if (array_key_exists('icons', $this->all())) {
                foreach ($this->icons as $icon) {
                    if (is_file($icon['image'])) {
                        $file = $icon['image'];
                        $extension = strtolower($file->getClientOriginalExtension());
                        if (!in_array($extension, ['jpeg', 'jpg', 'png', 'jfif'])) {
                            $validator->errors()->add('image', 'התמונה חייבת להיות מסוג: jpeg, png, jpg, jfif.');
                        }
                        if (!array_key_exists('position', $icon)) {
                            $validator->errors()->add('position', 'הכנס את מיקום התמונה');
                        }
                    }
                    if (!array_key_exists('replace', $icon)) {
                        $validator->errors()->add('replace', 'הכנס את התמונה שתרצה להחליף');
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'icons.required_without_all' => 'הכנס שדה אחד לעדכון',
            'description.required_without_all' => 'הכנס שדה אחד לעדכון',
            'icons.array' => 'האייקונים אינם בפורמט הנכון',
            'description.string' => 'התת כותרת אינו בפורמט הנכון',
        ];
    }
}
