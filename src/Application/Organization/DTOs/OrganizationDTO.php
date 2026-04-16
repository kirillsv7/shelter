<?php

namespace Source\Application\Organization\DTOs;

use JsonSerializable;
use Source\Domain\Organization\Aggregates\Organization;

final readonly class OrganizationDTO implements JsonSerializable
{
    public function __construct(
        public Organization $organization,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->organization->id,
            'name' => $this->organization->name,
            'address' => $this->organization->address->toArray(),
            'contacts' => $this->organization->contacts->toArray(),
            'socials' => $this->organization->socials->toArray(),
            'isVerified' => $this->organization->isVerified(),
            'isActive' => $this->organization->isActive(),
            'createdAt' => $this->organization->createdAt,
            'updatedAt' => $this->organization->updatedAt,
        ];
    }
}
