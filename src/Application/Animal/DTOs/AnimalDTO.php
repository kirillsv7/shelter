<?php

namespace Source\Application\Animal\DTOs;

use JsonSerializable;
use Source\Domain\Animal\Aggregates\Animal;

final readonly class AnimalDTO implements JsonSerializable
{
    public function __construct(
        public Animal $animal,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->animal->id,
            'info' => $this->animal->info,
            'age' => $this->animal->age()->value,
            'status' => $this->animal->status(),
            'isPublished' => $this->animal->IsPublished(),
            'createdAt' => $this->animal->createdAt,
            'updatedAt' => $this->animal->updatedAt,
        ];
    }
}
