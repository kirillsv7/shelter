<?php

namespace Source\Interface\MediaFile\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaFileStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => ['required', 'string'],
            'id' => ['required', 'string'],
            'file' => ['required', 'file']
        ];
    }
}
