<?php

namespace Source\Domain\Animal;

use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;

final readonly class AnimalSearchCriteria
{
    private function __construct(
        public ?Name $name,
        public ?AnimalType $type,
        public ?AnimalGender $gender,
        public ?IntegerValueObject $ageMin,
        public ?IntegerValueObject $ageMax,
    ) {
    }

    public static function create(
        Name $name = null,
        AnimalType $type = null,
        AnimalGender $gender = null,
        IntegerValueObject $ageMin = null,
        IntegerValueObject $ageMax = null,
    ): self {
        return new self(
            $name,
            $type,
            $gender,
            $ageMin,
            $ageMax
        );
    }
}
