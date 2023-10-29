<?php

namespace Source\Interface\MediaFile\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Source\Domain\MediaFile\Enums\MediableModel;

final class MediaFileStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => [
                'required',
                Rule::in(array_column(MediableModel::cases(), 'name'))
            ],
            'id' => ['required', 'string'],
            'file' => ['required', 'file']
        ];
    }
}
