<?php

namespace Source\Interface\Animal\DTOs;

use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Shared\Model\PaginationValueObjects\Limit;
use Source\Domain\Shared\Model\PaginationValueObjects\Page;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class AnimalIndexRequestDTO
{
    public function __construct(
        public ?StringValueObject $name,
        public ?AnimalType $type,
        public ?AnimalGender $gender,
        public ?IntegerValueObject $ageMin,
        public ?IntegerValueObject $ageMax,
        public ?Limit $limit,
        public ?Page $page,
    ) {
    }
}
