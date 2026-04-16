<?php

namespace Source\Interface\Organization\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Source\Domain\Shared\ValueObjects\EmailValueObject;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Interface\Organization\DTOs\OrganizationUpdateRequestDTO;

final class OrganizationUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'addressLine1' => ['required', 'string', 'max:255'],
            'addressLine2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'twitter' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'tiktok' => ['nullable', 'string', 'max:255'],
            'isVerified' => ['required', 'boolean'],
            'isActive' => ['required', 'boolean'],
        ];
    }

    public function getDTO(): OrganizationUpdateRequestDTO
    {
        return new OrganizationUpdateRequestDTO(
            name: StringValueObject::fromString($this->validated('name')),
            addressLine1: StringValueObject::fromString($this->validated('addressLine1')),
            addressLine2: $this->validated('addressLine2')
                ? StringValueObject::fromString($this->validated('addressLine2'))
                : null,
            city: StringValueObject::fromString($this->validated('city')),
            state: StringValueObject::fromString($this->validated('state')),
            postcode: StringValueObject::fromString($this->validated('postcode')),
            country: StringValueObject::fromString($this->validated('country')),
            phone: StringValueObject::fromString($this->validated('phone')),
            email: EmailValueObject::fromString($this->validated('email')),
            website: $this->validated('website')
                ? StringValueObject::fromString($this->validated('website'))
                : null,
            facebook: $this->validated('facebook')
                ? StringValueObject::fromString($this->validated('facebook'))
                : null,
            instagram: $this->validated('instagram')
                ? StringValueObject::fromString($this->validated('instagram'))
                : null,
            twitter: $this->validated('twitter')
                ? StringValueObject::fromString($this->validated('twitter'))
                : null,
            youtube: $this->validated('youtube')
                ? StringValueObject::fromString($this->validated('youtube'))
                : null,
            tiktok: $this->validated('tiktok')
                ? StringValueObject::fromString($this->validated('tiktok'))
                : null,
            isVerified: $this->validated('isVerified'),
            isActive: $this->validated('isActive'),
        );
    }
}
