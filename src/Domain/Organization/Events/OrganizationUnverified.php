<?php

namespace Source\Domain\Organization\Events;

use Source\Domain\Organization\Aggregates\Organization;

final readonly class OrganizationUnverified
{
    public function __construct(
        public Organization $organization,
    ) {
    }
}
