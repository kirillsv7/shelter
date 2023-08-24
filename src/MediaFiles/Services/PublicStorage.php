<?php

namespace Source\MediaFiles\Services;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Source\MediaFiles\DTOs\SavedFileDTO;

final class PublicStorage implements Storage
{
    private const DISK = 'public';

    public function __construct(
        private readonly FilesystemManager $fileSystem
    ) {
    }

    public function saveFile(UploadedFile $file, string $folderName = null): SavedFileDTO
    {
        $fileName = $file->getClientOriginalName();

        $filePath = $folderName ? $folderName.'/'.$fileName : $fileName;

        $result = $this->fileSystem
            ->disk(self::DISK)
            ->put(
                $filePath,
                $file->getContent()
            );

        if (! $result) {
            throw new \RuntimeException('File not saved');
        }

        return new SavedFileDTO(
            disk: self::DISK,
            path: $filePath,
        );
    }
}
