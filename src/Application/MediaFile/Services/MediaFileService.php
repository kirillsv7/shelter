<?php

namespace Source\Application\MediaFile\Services;

use Source\Domain\MediaFile\Contracts\Storage;

final readonly class MediaFileService
{
    public function __construct(
        protected Storage $storage,
    ) {
    }

    public function getUrl(
        string $fileRoute,
        string $fileName
    ): string {
        return $this->storage->getFileUrl(
            $fileRoute,
            $fileName,
        );
    }
}
