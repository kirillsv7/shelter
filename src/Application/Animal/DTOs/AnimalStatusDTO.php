<?php

namespace Source\Application\Animal\DTOs;

use JsonSerializable;
use Source\Domain\Animal\Aggregates\AnimalStatus;

final readonly class AnimalStatusDTO implements JsonSerializable
{
    public function __construct(
        protected AnimalStatus $animalStatus,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->animalStatus->id->toString(),
            'status' => $this->animalStatus->status->value,
            'description' => $this->animalStatus->notes,
            'created_at' => $this->animalStatus->createdAt->toIso8601String(),
        ];
    }
}
