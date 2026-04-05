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
            'id' => $this->animal->id(),
            'info' => $this->animal->info(),
            'age' => $this->animal->age()->value,
            'status' => $this->animal->status(),
            'published' => $this->animal->published(),
            'created_at' => $this->animal->createdAt(),
            'updated_at' => $this->animal->updatedAt(),
        ];
    }
}
