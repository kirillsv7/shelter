<?php

namespace Source\Domain\Organization\Events;

use Source\Domain\Organization\Aggregates\Organization;

final readonly class OrganizationActivated
{
    public function __construct(
        public Organization $organization,
    ) {
    }
}
