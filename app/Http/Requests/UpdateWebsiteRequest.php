<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteRequest extends FormRequest
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
            'name' => 'nullable|string|min:2',
            'link' => 'nullable|url',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            //
        ];
    }
    public function messages()
    {
        return [
            'name.string' => 'שם האתר חייב להיות מחרוזת.',
            'name.min' => 'שם האתר צריך להכיל לפחות שני תווים.',
            'link.url' => 'קישור האתר חייב להיות כתובת URL חוקית.',
            'image.file' => 'הקובץ חייב להיות תמונה.',
            'image.mimes' => 'התמונה חייבת להיות מסוג: jpeg, png, jpg, gif.',
            'image.max' => 'גודל התמונה לא יכול לעלות על 2MB.',
        ];
    }
}
