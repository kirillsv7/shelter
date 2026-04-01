<?php

namespace Source\Interface\Animal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Interface\Animal\DTOs\AnimalStatusUpdateRequestDTO;

final class AnimalStatusUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(AnimalStatus::class)],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function getDTO(): AnimalStatusUpdateRequestDTO
    {
        return new AnimalStatusUpdateRequestDTO(
            status: AnimalStatus::tryFrom($this->validated('status')),
            notes: $this->validated('notes')
                ? StringValueObject::fromString($this->validated('notes'))
                : null,
        );
    }
}
