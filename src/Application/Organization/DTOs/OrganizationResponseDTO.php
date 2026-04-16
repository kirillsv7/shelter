<?php

namespace Source\Application\Organization\DTOs;

use JsonSerializable;

final readonly class OrganizationResponseDTO implements JsonSerializable
{
    public function __construct(
        public OrganizationDetailsDTO $organization,
    ) {
    }

    public function jsonSerialize(): OrganizationDetailsDTO
    {
        return $this->organization;
    }
}
