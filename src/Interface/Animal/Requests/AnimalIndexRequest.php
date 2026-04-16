<?php

namespace Source\Interface\Animal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Shared\Model\PaginationValueObjects\Limit;
use Source\Domain\Shared\Model\PaginationValueObjects\Page;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;
use Source\Interface\Animal\DTOs\AnimalIndexRequestDTO;

final class AnimalIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string'],
            'type' => ['string'],
            'gender' => [new Enum(AnimalGender::class)],
            'age_min' => ['integer'],
            'age_max' => ['integer'],
            'limit' => ['integer'],
            'page' => ['integer'],
        ];
    }

    public function getDTO(): AnimalIndexRequestDTO
    {
        return new AnimalIndexRequestDTO(
            name: $this->validated('name')
                ? Name::fromString($this->validated('name'))
                : null,
            type: $this->validated('type')
                ? AnimalType::single($this->validated('type'))
                : null,
            gender: $this->validated('gender')
                ? AnimalGender::tryFrom($this->validated('gender'))
                : null,
            ageMin: $this->validated('age_min')
                ? IntegerValueObject::fromInteger($this->validated('age_min'))
                : null,
            ageMax: $this->validated('age_max')
                ? IntegerValueObject::fromInteger($this->validated('age_max'))
                : null,
            limit: $this->validated('limit')
                ? Limit::fromInteger($this->validated('limit'))
                : null,
            page: $this->validated('page')
                ? Page::fromInteger($this->validated('page'))
                : null,
        );
    }
}
