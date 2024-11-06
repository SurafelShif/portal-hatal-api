<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsitesRequest extends FormRequest
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
            '*.uuid' => 'required|string',
            '*.name' => 'nullable|string|min:2',
            '*.position' => 'nullable|integer|min:0',
            '*.description' => 'nullable|string|min:2',
            '*.link' => 'nullable|url|unique:websites,link',
            '*.image' => 'nullable|file|mimes:jpeg,png,jpg|max:10248',
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
                return $validator->errors()->add('', 'הכנס לפחות אתר אחד לעדכון');
            }
            foreach ($this->all() as $key => $item) {
                if (!isset($item['name']) && !isset($item['position']) && !isset($item['description']) && !isset($item['link']) && !isset($item['image'])) {
                    $validator->errors()->add("$key", 'הכנס לפחות ערך אחד לעדכון');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            '*.uuid.required' => 'נא לשלוח את מזהה האתר (UUID)',
            '*.name.string' => 'נא לשלוח את שם האתר באורך של לפחות 2 תווים',
            '*.position.integer' => 'נא לשלוח את המיקום כמספר שלם',
            '*.description.string' => 'נא לשלוח את התיאור באורך של לפחות 2 תווים',
            '*.link.url' => 'נא לשלוח קישור חוקי',
            '*.link.unique' => 'קישור זה כבר קיים במערכת',
            '*.image.file' => 'נא להעלות קובץ תמונה בפורמט נכון',
            '*.image.mimes' => 'נא להעלות קובץ תמונה בפורמט jpeg, png, או jpg בלבד',
            '*.image.max' => 'גודל הקובץ המקסימלי הוא 10MB',
        ];
    }
}
