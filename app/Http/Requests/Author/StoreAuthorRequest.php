<?php

namespace App\Http\Requests\Author;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'full_name' => 'required|array',
            'full_name.*' => 'nullable|string|max:100',
            'about' => 'required|array',
            'about.*' => 'nullable|string|max:500',
            'image' => 'required|file|mimes:png,jpg,jpeg,svg|max:5070'
        ];
    }
}
