<?php

namespace Source\Domain\MediaFile\Services;

use Illuminate\Http\UploadedFile;
use Source\Infrastructure\MediaFile\DTOs\SavedFileDTO;

interface Storage
{
    public function saveFile(UploadedFile $file, string $folderName): SavedFileDTO;
}
