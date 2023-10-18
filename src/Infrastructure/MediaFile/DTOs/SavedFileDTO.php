<?php

namespace Source\Infrastructure\MediaFile\DTOs;

readonly class SavedFileDTO
{
    public function __construct(
        public string $disk,
        public string $path
    ) {
    }
}
