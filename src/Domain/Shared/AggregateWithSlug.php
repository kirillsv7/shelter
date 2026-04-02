<?php

namespace Source\Domain\Shared;

use Source\Domain\Slug\ValueObjects\SlugString;

interface AggregateWithSlug
{
    public function addSlug(SlugString $slug): void;
}
