<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteRequest extends FormRequest
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
            'name' => 'required|string|min:2',
            'description' => 'required|string|min:2',
            'link' => 'required|url',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
            //
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'שם האתר דרוש.',
            'name.string' => 'שם האתר חייב להיות מחרוזת.',
            'name.min' => 'שם האתר צריך להכיל לפחות שני תווים.',
            'link.required' => 'קישור האתר דרוש.',
            'description.required' => 'תיאור האתר דרוש.',
            'description.string' => 'תיאור האתר חייב להיות מחרוזת.',
            'description.min' => 'תיאור האתר צריך להכיל לפחות שני תווים.',
            'link.required' => 'קישור האתר דרוש.',
            'link.url' => 'קישור האתר חייב להיות כתובת URL חוקית.',
            'image.required' => 'דרוש קובץ תמונה.',
            'image.file' => 'התמונה חייבת להיות קובץ.',
            'image.mimes' => 'התמונה חייבת להיות מסוג: jpeg, png, jpg, gif.',
            'image.max' => 'גודל התמונה לא יכול לעלות על 2MB.',
        ];
    }
}
