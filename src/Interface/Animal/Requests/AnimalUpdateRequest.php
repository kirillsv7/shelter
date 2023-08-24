<?php

namespace Source\Interface\Animal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;

final class AnimalUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'type' => [new Enum(AnimalType::class)],
            'gender' => [new Enum(AnimalGender::class)],
            'breed' => ['string'],
            'birthdate' => ['date'],
            'entrydate' => ['date'],
        ];
    }
}
