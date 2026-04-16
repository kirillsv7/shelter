<?php

namespace Source\Interface\Organization\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Source\Domain\Shared\Model\PaginationValueObjects\Limit;
use Source\Domain\Shared\Model\PaginationValueObjects\Page;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Interface\Organization\DTOs\OrganizationIndexRequestDTO;

final class OrganizationIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['string'],
            'isVerified' => ['bool'],
            'isActive' => ['bool'],
            'limit' => ['integer'],
            'page' => ['integer'],
        ];
    }

    public function getDTO(): OrganizationIndexRequestDTO
    {
        return new OrganizationIndexRequestDTO(
            text: $this->validated('text')
                ? StringValueObject::fromString($this->validated('text'))
                : null,
            isVerified: $this->validated('isVerified'),
            isActive: $this->validated('isActive'),
            limit: $this->validated('limit')
                ? Limit::fromInteger($this->validated('limit'))
                : null,
            page: $this->validated('page')
                ? Page::fromInteger($this->validated('page'))
                : null,
        );
    }
}
