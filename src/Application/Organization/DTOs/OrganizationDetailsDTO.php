<?php

namespace Source\Application\Organization\DTOs;

use JsonSerializable;
use Source\Application\Slug\DTOs\SlugDTO;

final readonly class OrganizationDetailsDTO implements JsonSerializable
{
    public function __construct(
        public OrganizationDTO $organizationDTO,
        public SlugDTO $slugDTO,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'organization' => $this->organizationDTO,
            'slug' => $this->slugDTO,
        ];
    }
}
