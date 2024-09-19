<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'personal_id' => 'required|string|unique:users|size:9',
            'personal_number' => 'required|string|unique:users|size:7',
            'full_name' => 'required|string|min:2',
        ];
    }
    public function messages(): array
    {
        return [
            'personal_id.required' => 'נא לספק תעודת זהות',
            'personal_id.string' => 'תעודת זהות חייב להיות מחרוזת',
            'personal_id.unique' => 'תעודת זהות זה כבר קיים במערכת',
            'personal_id.size' => 'תעודת זהות חייב להיות בדיוק 9 ספרות',

            'personal_number.required' => 'נא לספק מספר אישי ',
            'personal_number.string' => 'מספר אישי  חייב להיות מחרוזת',
            'personal_number.unique' => 'מספר אישי נוסף זה כבר קיים במערכת',
            'personal_number.size' => 'מספר אישי  חייב להיות בדיוק 7 ספרות',

            'full_name.required' => 'נא לספק שם מלא',
            'full_name.string' => 'שם מלא חייב להיות מחרוזת',
            'full_name.min' => 'שם מלא חייב להכיל לפחות 2 תווים',
        ];
    }
}
