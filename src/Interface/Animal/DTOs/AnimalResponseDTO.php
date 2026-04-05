<?php

namespace Source\Interface\Animal\DTOs;

use JsonSerializable;
use Source\Application\Animal\DTOs\AnimalDetailsDTO;

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
