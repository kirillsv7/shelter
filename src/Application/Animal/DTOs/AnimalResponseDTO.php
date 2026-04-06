<?php

namespace Source\Application\Animal\DTOs;

use JsonSerializable;

final readonly class AnimalResponseDTO implements JsonSerializable
{
    public function __construct(
        public AnimalDetailsDTO $animal,
    ) {
    }

    public function jsonSerialize(): AnimalDetailsDTO
    {
        return $this->animal;
    }
}
