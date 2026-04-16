<?php

namespace Source\Domain\Organization\Search;

use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class OrganizationSearchCriteria
{
    public function __construct(
        public ?StringValueObject $text,
        public ?bool $isVerified,
        public ?bool $isActive,
    ) {
    }
}
