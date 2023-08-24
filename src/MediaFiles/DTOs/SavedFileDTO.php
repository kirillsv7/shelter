<?php

namespace Source\MediaFiles\DTOs;

readonly class SavedFileDTO
{
    public function __construct(
        public string $disk,
        public string $path
    ) {
    }
}
