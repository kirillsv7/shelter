<?php

namespace Source\Application\Slug\DTOs;

use JsonSerializable;

final readonly class SlugResponseDTO implements JsonSerializable
{
    public function __construct(
        public SlugDTO $slugDTO,
    ) {
    }

    public function jsonSerialize(): SlugDTO
    {
        return $this->slugDTO;
    }
}
