<?php

namespace Source\Domain\Organization\Events;

use Source\Domain\Organization\Aggregates\Organization;

final readonly class OrganizationDeleted
{
    public function __construct(
        public Organization $organization,
    ) {
    }
}
