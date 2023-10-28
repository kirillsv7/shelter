<?php

namespace Source\Domain\Animal\Events;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\ValueObjects\Name;

final readonly class AnimalStatusChanged
{
    public function __construct(
        public UuidInterface $id,
        public Name $name,
        public AnimalStatus $newStatus,
        public AnimalStatus $oldStatus,
    ) {
    }
}
