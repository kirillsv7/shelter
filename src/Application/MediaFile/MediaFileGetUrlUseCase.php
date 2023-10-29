<?php

namespace Source\Application\MediaFile;

use Source\Domain\MediaFile\Contracts\Storage;

final class MediaFileGetUrlUseCase
{
    public function __construct(
        public Storage $storage
    ) {
    }

    public function __invoke(
        string $fileRoute,
        string $fileName
    ): string {
        return $this->storage->getFileUrl(
            $fileRoute,
            $fileName
        );
    }
}
