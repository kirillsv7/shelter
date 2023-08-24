<?php

namespace Source\Domain\Animal;

use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;

final readonly class AnimalSearchCriteria
{
    private function __construct(
        public ?AnimalType $type,
        public ?AnimalGender $gender,
    ) {
    }

    public static function create(
        ?AnimalType $type = null,
        ?AnimalGender $gender = null
    ): self {
        return new self(
            $type,
            $gender,
        );
    }
}
