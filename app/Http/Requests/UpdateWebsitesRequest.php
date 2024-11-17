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
            '*.uuid' => 'required',
            '*.name' => 'nullable|unique:websites,name',
            '*.position' => 'nullable|integer|min:0',
            '*.description' => 'nullable',
            '*.link' => 'nullable|unique:websites,link',
            '*.image' => 'nullable|file|max:10248',
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
                if (!isset($item['name']) && !isset($item['position']) && !isset($item['description']) && !isset($item['link']) && !array_key_exists('image', $item)) {
                    $validator->errors()->add("$key", 'הכנס לפחות ערך אחד לעדכון');
                }

                if ($this->hasFile("{$key}.image")) {
                    $file = $this->file("{$key}.image");
                    $extension = $file->getClientOriginalExtension();
                    if (!in_array($extension, ['jpeg', 'jpg', 'png', 'jfif'])) {
                        $validator->errors()->add("{$key}.image", 'התמונה חייבת להיות מסוג: jpeg, png, jpg, jfif.');
                    }
                }
            }
        });
    }


    public function messages(): array
    {
        return [
            '*.uuid.required' => 'נא לשלוח את מזהה האתר (UUID)',
            '*.position.integer' => 'נא לשלוח את המיקום בפורמט המתאים',
            '*.link.unique' => 'קישור זה כבר קיים במערכת',
            '*.name.unique' => 'שם זה כבר קיים במערכת',
            '*.image.file' => 'נא להעלות קובץ תמונה בפורמט נכון',
            '*.image.max' => 'גודל הקובץ המקסימלי הוא 10MB',
        ];
    }
}
