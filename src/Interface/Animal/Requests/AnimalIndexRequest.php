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
    public function rules()
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
            name: $this->input('name')
                ? Name::fromString($this->input('name'))
                : null,
            type: $this->input('type')
                ? AnimalType::single($this->input('type'))
                : null,
            gender: $this->input('gender')
                ? AnimalGender::tryFrom($this->input('gender'))
                : null,
            ageMin: $this->input('age_min')
                ? IntegerValueObject::fromInteger($this->input('age_min'))
                : null,
            ageMax: $this->input('age_max')
                ? IntegerValueObject::fromInteger($this->input('age_max'))
                : null,
            limit: $this->input('limit')
                ? Limit::fromInteger($this->input('limit'))
                : null,
            page: $this->input('page')
                ? Page::fromInteger($this->input('page'))
                : null,
        );
    }
}
