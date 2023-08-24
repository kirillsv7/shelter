<?php

namespace Source\Interface\Animal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;

final class AnimalStoreRequest extends FormRequest
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
}
