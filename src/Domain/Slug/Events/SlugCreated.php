<?php

namespace Source\Domain\Slug\Events;

final readonly class SlugCreated
{
    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
