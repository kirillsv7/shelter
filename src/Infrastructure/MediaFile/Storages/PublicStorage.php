<?php

namespace Source\Infrastructure\MediaFile\Storages;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Source\Domain\MediaFile\Contracts\Storage;
use Source\Domain\MediaFile\ValueObjects\StorageInfo;
use Source\Domain\Shared\ValueObjects\PathValueObject;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final class PublicStorage implements Storage
{
    protected const string DISK = 'public';

    public function __construct(
        private readonly FilesystemManager $fileSystem
    ) {
    }

    public function saveFile(
        UploadedFile $file,
        string $fileRoute,
        string $fileName,
    ): StorageInfo {
        $path = PathValueObject::fromArray([
            $fileRoute,
            $fileName,
        ]);

        $result = $this->fileSystem
            ->disk(self::DISK)
            ->put(
                $path->value(),
                $file->getContent(),
            );

        if (!$result) {
            throw new RuntimeException('File not saved');
        }

        return new StorageInfo(
            disk: StringValueObject::fromString(self::DISK),
            route: PathValueObject::fromString($fileRoute),
            fileName: StringValueObject::fromString($fileName),
        );
    }

    public function getFileUrl(
        string $fileRoute,
        string $fileName
    ): string {
        return $this->fileSystem->url($fileRoute . DIRECTORY_SEPARATOR . $fileName);
    }
}
