<?php

namespace Source\Domain\Slug\Events;

use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\ValueObjects\SlugString;

final readonly class SlugUpdated
{
    public function __construct(
        public Slug $slug,
        public SlugString $oldValue,
    ) {
    }
}
