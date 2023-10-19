<?php

namespace Source\Domain\MediaFile\ValueObjects;

final readonly class SavedFile
{
    public function __construct(
        public string $disk,
        public string $path
    ) {
    }
}
