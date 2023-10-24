<?php

namespace Source\Domain\MediaFile\Contracts;

use Illuminate\Http\UploadedFile;
use Source\Domain\MediaFile\ValueObjects\SavedFile;

interface Storage
{
    public function saveFile(UploadedFile $file, string $filePath): SavedFile;

    public function getFileUrl(string $path): string;
}
