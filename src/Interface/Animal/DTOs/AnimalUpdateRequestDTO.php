<?php

namespace Source\Interface\Animal\DTOs;

use Carbon\CarbonImmutable;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;

final readonly class AnimalUpdateRequestDTO
{
    public function __construct(
        public Name $name,
        public AnimalType $type,
        public AnimalGender $gender,
        public Breed $breed,
        public CarbonImmutable $birthdate,
        public CarbonImmutable $entrydate,
    ) {
    }
}
