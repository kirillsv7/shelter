<?php

namespace Source\Domain\MediaFile\Contracts;

use Illuminate\Http\UploadedFile;
use Source\Domain\MediaFile\ValueObjects\StorageInfo;

interface Storage
{
    public function saveFile(
        UploadedFile $file,
        string $fileRoute,
        string $fileName
    ): StorageInfo;

    // TODO: Rewrite implementation
    public function getFileUrl(
        string $fileRoute,
        string $fileName
    ): string;
}
