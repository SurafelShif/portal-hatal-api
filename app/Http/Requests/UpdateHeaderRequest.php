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
            'icons.*.position' => 'required|integer',
            'icons.*.id' => 'required|integer'
        ];
    }


    public function messages(): array
    {
        return [
            'icons.required_without_all' => 'הכנס שדה אחד לעדכון', // At least one of the fields (icons or description) is required
            'description.required_without_all' => 'הכנס שדה אחד לעדכון', // At least one of the fields (icons or description) is required
            'icons.array' => 'האייקונים אינם בפורמט הנכון', // Icons must be an array
            'icons.*.position.required' => 'יש להכניס מיקום לכל אייקון', // Position is required for each icon
            'icons.*.id.required' => 'יש להכניס מזהה לכל אייקון', // ID is required for each icon
            'description.string' => 'התת כותרת אינה בפורמט הנכון', // Description must be a string
        ];
    }
}
