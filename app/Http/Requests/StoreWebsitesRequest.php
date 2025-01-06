<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsitesRequest extends FormRequest
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
            '*.name' => 'required|unique:websites,name',
            '*.position' => 'required|integer|min:0',
            '*.description' => 'required',
            '*.link' => 'required|unique:websites,link',
            '*.image' => 'required|file|max:2048',
        ];
    }
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($this->all()) === 0) {
                return $validator->errors()->add('', 'הכנס לפחות אתר אחד להעלאה');
            }

            foreach ($this->all() as $key => $item) {
                if (!isset($item['name']) || !isset($item['position']) || !isset($item['description']) || !isset($item['link']) || !array_key_exists('image', $item)) {
                    $validator->errors()->add("$key", 'הכנס לפחות ערך אחד לעדכון');
                }

                if ($this->hasFile("{$key}.image")) {
                    $file = $this->file("{$key}.image");
                    $extension = strtolower($file->getClientOriginalExtension());
                    if (!in_array($extension, ['jpeg', 'jpg', 'png', 'jfif'])) {
                        $validator->errors()->add("{$key}.image", 'התמונה חייבת להיות מסוג: jpeg, png, jpg, jfif.');
                    }
                }
            }
        });
    }
    public function messages()
    {
        return [
            '*.name.required' => 'שם האתר דרוש.',
            '*.position.required' => 'מיקום האתר דרוש.',
            '*.position.min' => 'מיקום האתר צריך להיות מינימום 0.',
            '*.link.required' => 'קישור האתר דרוש.',
            '*.name.unique' => 'שם זה כבר קיים במערכת',
            '*.link.unique' => 'קישור האתר קיים במערכת.',
            '*.description.required' => 'תיאור האתר דרוש.',
            '*.image.required' => 'דרוש קובץ תמונה.',
            '*.image.file' => 'התמונה חייבת להיות קובץ.',
            '*.image.max' => 'גודל התמונה לא יכול לעלות על 2MB.',
        ];
    }
}
