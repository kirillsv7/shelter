<?php

namespace Source\Interface\Animal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;

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

    public function getName(): ?Name
    {
        return $this->input('name')
            ? Name::fromString($this->input('name'))
            : null;
    }

    public function getType(): ?AnimalType
    {
        return $this->input('type')
            ? AnimalType::single($this->input('type'))
            : null;
    }

    public function getGender(): ?AnimalGender
    {
        return $this->input('gender')
            ? AnimalGender::tryFrom($this->input('gender'))
            : null;
    }

    public function getAgeMin(): ?IntegerValueObject
    {
        return $this->input('age_min')
            ? IntegerValueObject::fromInteger($this->input('age_min'))
            : null;
    }

    public function getAgeMax(): ?IntegerValueObject
    {
        return $this->input('age_max')
            ? IntegerValueObject::fromInteger($this->input('age_max'))
            : null;
    }

    public function getLimit(): ?int
    {
        return $this->input('limit');
    }

    public function getPage(): ?int
    {
        return $this->input('page');
    }
}
