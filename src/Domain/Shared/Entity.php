<?php

namespace Source\Domain\Shared;

use Ramsey\Uuid\UuidInterface;

interface Entity
{
    public function id(): UuidInterface;

    public function toArray(): array;
}
