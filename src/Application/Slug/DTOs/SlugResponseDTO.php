<?php

namespace Source\Application\Slug\DTOs;

use JsonSerializable;

final readonly class SlugResponseDTO implements JsonSerializable
{
    public function __construct(
        public SlugDTO $slug,
    ) {
    }

    public function jsonSerialize(): SlugDTO
    {
        return $this->slug;
    }
}
