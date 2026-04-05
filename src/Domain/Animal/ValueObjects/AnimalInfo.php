<?php

namespace Source\Domain\Animal\ValueObjects;

use Carbon\CarbonInterface;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;

final readonly class AnimalInfo
{
    public function __construct(
        public Name $name,
        public AnimalType $type,
        public AnimalGender $gender,
        public Breed $breed,
        public CarbonInterface $birthdate,
        public CarbonInterface $entrydate,
    ) {
    }

    public function change(
        ?Name $name,
        ?AnimalType $type,
        ?AnimalGender $gender,
        ?Breed $breed,
        ?CarbonInterface $birthdate,
        ?CarbonInterface $entrydate,
    ): self {
        return new self(
            name: $name ?? $this->name,
            type: $type ?? $this->type,
            gender: $gender ?? $this->gender,
            breed: $breed ?? $this->breed,
            birthdate: $birthdate ?? $this->birthdate,
            entrydate: $entrydate ?? $this->entrydate,
        );
    }
}
