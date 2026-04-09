<?php

namespace Source\Domain\MediaFile\Events;

use Source\Domain\MediaFile\Aggregates\MediaFile;

final readonly class MediaFileCreated
{
    public function __construct(
        public MediaFile $mediaFile,
    ) {
    }
}
