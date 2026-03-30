<?php

namespace Source\Interface\Animal\Mappers;

use Source\Domain\Animal\Aggregates\Animal;

final readonly class AnimalMapper
{
    public function __construct(
        protected AnimalInfoMapper $animalInfoMapper,
    ) {
    }

    public function toArray(Animal $animal): array
    {
        return [
            'id' => $animal->id(),
            'info' => $this->animalInfoMapper->toArray($animal->info()),
            'age' => $animal->age()->value,
            'status' => $animal->status(),
            'published' => $animal->published(),
            'created_at' => $animal->createdAt(),
            'updated_at' => $animal->updatedAt(),
            'slug' => $animal->slug()?->value(),
        ];
    }
}
