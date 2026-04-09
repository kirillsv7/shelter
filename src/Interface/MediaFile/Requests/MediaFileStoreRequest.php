<?php

namespace Source\Interface\MediaFile\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;
use Source\Infrastructure\MediaFile\Enums\MediableModel;
use Source\Interface\MediaFile\DTOs\MediaFileStoreRequestDTO;

final class MediaFileStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => [
                'required',
                Rule::in(array_column(MediableModel::cases(), 'name')),
            ],
            'id' => ['required', 'string'],
            'file' => ['required', 'file'],
        ];
    }

    public function getDTO(): MediaFileStoreRequestDTO
    {
        return new MediaFileStoreRequestDTO(
            model: MediableModel::fromName($this->validated('model')),
            id: Uuid::fromString($this->validated('id')),
            file: $this->validated('file'),
        );
    }
}
