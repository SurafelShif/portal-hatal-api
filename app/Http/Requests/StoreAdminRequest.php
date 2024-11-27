<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
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
            '*.full_name' => 'required|min:2',
            '*.personal_number' => 'required|integer|regex:/^\d{7,8}$/',
        ];
    }

    public function messages()
    {
        return [
            '*.full_name.required' => 'שם המשתמש חסר',
            '*.full_name.min' => 'שם המשתמש צריך להיות לפחות שני תווים',
            '*.personal_number.required' => 'המספר האישי חסר',
            '*.personal_number.integer' => 'המספר האישי חסר',
            '*.personal_number.regex' => 'המספר האישי בפורמט לא נכון',

        ];
    }
}
