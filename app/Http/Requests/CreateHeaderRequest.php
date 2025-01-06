<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHeaderRequest extends FormRequest
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
            '*.image' => 'required|max:10248',
            '*.position' => 'required|integer',
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

    public function messages(): array
    {
        return [
            '*.image.required' => 'התמונה הינה חובה',
            '*.position.required' => 'מיקום התמונה הינה חובה',
            '*.image.file' => 'התמונה חייבת להיות קובץ',
            '*.image.max' => 'גודל הקובץ המקסימלי הוא 10MB',
            '*.position.integer' => 'מיקום התמונה אינה בפורמט הנכון',
        ];
    }
}
