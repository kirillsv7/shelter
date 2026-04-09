<?php

namespace Source\Domain\Animal\Events;

use Source\Domain\Animal\Aggregates\Animal;

final readonly class AnimalCreated
{
    public function __construct(
        public Animal $animal,
    ) {
    }
}
