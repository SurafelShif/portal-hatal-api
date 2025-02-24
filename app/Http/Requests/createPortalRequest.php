<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createPortalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "display_name" => "required|string",
            "path" => "required|string|unique:portals,path"
        ];
    }
    public function messages(): array
    {
        return [
            "display_name.required" => "שם לתצוגה הינו חובה",
            "display_name.string" => "שם תצוגה אינו בפורנט הנכון",
            "path.required" => "נתיב הפורטל הינו חובה",
            "path.string" => "נתיב הפורטל אינו בפורנט הנכון",
            "path.unique" => "נתיב הפורטל קיים",

        ];
    }
}
