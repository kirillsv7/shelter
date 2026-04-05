<?php

namespace Source\Application\Slug\DTOs;

use JsonSerializable;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\ValueObjects\SlugString;

final readonly class SlugDTO implements JsonSerializable
{
    public function __construct(
        protected Slug $slug
    ) {
    }


    public function jsonSerialize(): SlugString
    {
        return  $this->slug->value();
    }
}
