<?php

namespace Source\Interface\Slug\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SlugUpdateRequest extends FormRequest
{
    /**
     * @return array<string , array<int, string>>
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255'],
        ];
    }
}
