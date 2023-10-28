<?php

namespace Source\Domain\Animal\Events;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\ValueObjects\Name;

final readonly class AnimalCreated
{
    public function __construct(
        public UuidInterface $id,
        public Name $name
    ) {
    }
}
