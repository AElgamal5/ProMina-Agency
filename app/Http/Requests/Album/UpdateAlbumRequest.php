<?php

namespace App\Http\Requests\Album;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAlbumRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'min:2', 'max:255'],
            'description' => ['sometimes', 'string', 'max:510'],
            'pictures' => ['sometimes', 'array', 'min:1', 'max:20'],

            'pictures.*.title' => ['required', 'string', 'min:1', 'max:255'],
            'pictures.*.content' => ['required', 'file', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:10240'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->replace(
            $this->only([
                'title',
                'description',
                'pictures',
            ])
        );
    }
}
