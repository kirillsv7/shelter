<?php

namespace Source\Domain\Animal\Events;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Enums\AnimalStatus;

final readonly class AnimalStatusChanged
{
    public function __construct(
        private UuidInterface $id,
        private AnimalStatus $status
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getStatus(): AnimalStatus
    {
        return $this->status;
    }
}
