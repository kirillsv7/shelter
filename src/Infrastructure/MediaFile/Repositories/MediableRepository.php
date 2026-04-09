<?php

namespace Source\Infrastructure\MediaFile\Repositories;

use Ramsey\Uuid\UuidInterface;

interface MediableRepository
{
    public function exists(UuidInterface $id): bool;
}
