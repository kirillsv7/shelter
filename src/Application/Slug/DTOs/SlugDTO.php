<?php

namespace Source\Application\Slug\DTOs;

use JsonSerializable;
use Source\Domain\Slug\Aggregates\Slug;

final readonly class SlugDTO implements JsonSerializable
{
    public function __construct(
        protected Slug $slug,
    ) {
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->slug->id,
            'slug' => $this->slug->value(),
            'sluggable_type' => $this->slug->sluggableType,
            'sluggable_uuid' => $this->slug->sluggableId,
            'created_at' => $this->slug->createdAt,
            'updated_at' => $this->slug->updatedAt,
        ];
    }
}
