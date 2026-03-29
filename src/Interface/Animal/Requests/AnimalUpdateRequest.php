<?php

namespace Source\Interface\Animal\Requests;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Interface\Animal\DTOs\AnimalUpdateRequestDTO;

final class AnimalUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', new Enum(AnimalType::class)],
            'gender' => ['required', new Enum(AnimalGender::class)],
            'breed' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
            'entrydate' => ['required', 'date'],
        ];
    }

    public function getDTO(): AnimalUpdateRequestDTO
    {
        return new AnimalUpdateRequestDTO(
            name: Name::fromString($this->validated('name')),
            type: AnimalType::from($this->validated('type')),
            gender: AnimalGender::from($this->validated('gender')),
            breed: Breed::fromString($this->validated('breed')),
            birthdate: new CarbonImmutable($this->validated('birthdate')),
            entrydate: new CarbonImmutable($this->validated('entrydate')),
        );
    }
}
