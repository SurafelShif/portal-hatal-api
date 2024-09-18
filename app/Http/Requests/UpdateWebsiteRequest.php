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
            'name.string' => 'The site name must be a string.',
            'name.min' => 'The site name should have atleast two characters',
            'link.url' => 'The site link must be a valid URL.',
            'image.file' => 'The image must be a file.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image size cannot exceed 2MB.',
        ];
    }
}
