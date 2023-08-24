<?php

namespace Source\Domain\Animal\Events;

use Ramsey\Uuid\UuidInterface;

final readonly class AnimalPublished
{
    public function __construct(
        private UuidInterface $id
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
}
