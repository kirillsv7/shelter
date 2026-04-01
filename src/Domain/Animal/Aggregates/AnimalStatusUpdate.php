<?php

namespace Source\Domain\Animal\Aggregates;

use Carbon\CarbonInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class AnimalStatusUpdate
{
    protected function __construct(
        public UuidInterface $id,
        public UuidInterface $animalId,
        public AnimalStatus $status,
        public ?StringValueObject $notes,
        public ?CarbonInterface $createdAt = null,
        public ?CarbonInterface $updatedAt = null,
    ) {
    }

    public static function make(
        UuidInterface $id,
        UuidInterface $animalId,
        AnimalStatus $status,
        ?StringValueObject $notes,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
    ): self {
        return new self(
            id: $id,
            animalId: $animalId,
            status: $status,
            notes: $notes,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public static function create(
        UuidInterface $id,
        UuidInterface $animalId,
        AnimalStatus $status,
        ?StringValueObject $notes,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
    ): self {
        return self::make(
            id: $id,
            animalId: $animalId,
            status: $status,
            notes: $notes,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }
}
