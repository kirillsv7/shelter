<?php

namespace Source\Domain\Slug\ValueObjects;

use Illuminate\Support\Str;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class SlugString extends StringValueObject
{
    protected function __construct(
        private string $value
    ) {
        parent::__construct(Str::slug($this->value));
    }
}
