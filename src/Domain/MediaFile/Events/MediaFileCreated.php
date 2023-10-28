<?php

namespace Source\Domain\MediaFile\Events;

use Ramsey\Uuid\UuidInterface;

final readonly class MediaFileCreated
{
    public function __construct(
        public UuidInterface $id
    ) {
    }
}
