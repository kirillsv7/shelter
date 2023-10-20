<?php

namespace Source\Infrastructure\MediaFile\Storages;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Source\Domain\MediaFile\Contracts\Storage;
use Source\Domain\MediaFile\ValueObjects\SavedFile;

final class PublicStorage implements Storage
{
    private const DISK = 'public';

    public function __construct(
        private readonly FilesystemManager $fileSystem
    ) {
    }

    public function saveFile(UploadedFile $file, string $filePath): SavedFile
    {
        $result = $this->fileSystem
            ->disk(self::DISK)
            ->put(
                $filePath,
                $file->getContent()
            );

        if (!$result) {
            throw new \RuntimeException('File not saved');
        }

        return new SavedFile(
            disk: self::DISK,
            path: $filePath,
        );
    }
}
