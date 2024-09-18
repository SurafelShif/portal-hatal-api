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
            'link' => 'required|url',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
            //
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The site name is required.',
            'name.string' => 'The site name must be a string.',
            'name.min' => 'The site name should have atleast two characters',
            'link.required' => 'The site link is required.',
            'link.url' => 'The site link must be a valid URL.',
            'image.required' => 'An image file is required.',
            'image.file' => 'The image must be a file.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image size cannot exceed 2MB.',
        ];
    }
}
