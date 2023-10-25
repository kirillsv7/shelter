<?php

namespace Source\Application\MediaFile;

use Source\Domain\MediaFile\Contracts\Storage;

class MediaFileGetUrlUseCase
{
    public function __construct(
        public Storage $storage
    ) {
    }

    public function __invoke(string $path): string
    {
        return $this->storage->getFileUrl($path);
    }
}
