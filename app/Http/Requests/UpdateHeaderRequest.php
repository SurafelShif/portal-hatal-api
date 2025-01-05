<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;
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
            'icons' => 'nullable|array',
            'description' => 'nullable|string',
            'icons.*.position' => 'required|integer',
            'icons.*.id' => 'required|integer',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasIcons = $this->filled('icons') && is_array($this->input('icons'));
            $hasDescription = array_key_exists('description', $this->all());

            if (!$hasIcons && !$hasDescription) {
                $validator->errors()->add('general', 'הכנס שדה אחד לעדכון');
            }
        });
    }



    public function messages(): array
    {
        return [
            'icons.required_without' => 'הכנס שדה אחד לעדכון',
            'description.required_without' => 'הכנס שדה אחד לעדכון',
            'icons.array' => 'האייקונים אינם בפורמט הנכון',
            'icons.*.position.required' => 'יש להכניס מיקום לכל אייקון',
            'icons.*.id.required' => 'יש להכניס מזהה לכל אייקון',
            'description.string' => 'התת כותרת אינה בפורמט הנכון',
        ];
    }
}
