<?php

namespace Source\Application\Animal\DTOs;

use JsonSerializable;
use Source\Domain\Animal\Aggregates\AnimalStatusUpdate;

final readonly class AnimalStatusUpdateDTO implements JsonSerializable
{
    public function __construct(
        protected AnimalStatusUpdate $animalStatusUpdate,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->animalStatusUpdate->id->toString(),
            'status' => $this->animalStatusUpdate->status->value,
            'description' => $this->animalStatusUpdate->notes,
            'created_at' => $this->animalStatusUpdate->createdAt->toIso8601String(),
        ];
    }
}
