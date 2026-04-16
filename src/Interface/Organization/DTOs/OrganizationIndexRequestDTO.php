<?php

namespace Source\Interface\Organization\DTOs;

use Source\Domain\Shared\Model\PaginationValueObjects\Limit;
use Source\Domain\Shared\Model\PaginationValueObjects\Page;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class OrganizationIndexRequestDTO
{
    public function __construct(
        public ?StringValueObject $text,
        public ?bool $isVerified,
        public ?bool $isActive,
        public ?Limit $limit,
        public ?Page $page,
    ) {
    }
}
