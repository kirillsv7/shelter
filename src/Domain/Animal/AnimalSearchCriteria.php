<?php

namespace Source\Domain\Animal;

use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Name;

final readonly class AnimalSearchCriteria
{
    private function __construct(
        public ?Name $name,
        public ?AnimalType $type,
        public ?AnimalGender $gender,
    ) {
    }

    public static function create(
        Name $name = null,
        AnimalType $type = null,
        AnimalGender $gender = null
    ): self {
        return new self(
            $name,
            $type,
            $gender,
        );
    }
}
