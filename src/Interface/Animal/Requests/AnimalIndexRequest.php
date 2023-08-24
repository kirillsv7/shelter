<?php

namespace Source\Interface\Animal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Source\Domain\Animal\Enums\AnimalGender;

final class AnimalIndexRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => ['string'],
            'gender' => [new Enum(AnimalGender::class)],
            'page' => ['integer'],
        ];
    }
}
