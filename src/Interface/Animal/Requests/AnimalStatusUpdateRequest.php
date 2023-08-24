<?php

namespace Source\Interface\Animal\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Source\Domain\Animal\Enums\AnimalStatus;

final class AnimalStatusUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(AnimalStatus::class)],
        ];
    }
}
