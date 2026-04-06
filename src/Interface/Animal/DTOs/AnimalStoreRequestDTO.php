<?php

namespace Source\Interface\Animal\DTOs;

use Carbon\CarbonImmutable;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\ValueObjects\Breed;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class AnimalStoreRequestDTO
{
    public function __construct(
        public Name $name,
        public AnimalType $type,
        public AnimalGender $gender,
        public Breed $breed,
        public CarbonImmutable $birthdate,
        public CarbonImmutable $entrydate,
        public AnimalStatus $status,
        public ?StringValueObject $notes,
        public bool $published,
    ) {
    }
}
