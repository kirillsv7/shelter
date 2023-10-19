<?php

namespace Source\Domain\MediaFile\Services;

use Illuminate\Http\UploadedFile;
use Source\Domain\MediaFile\ValueObjects\SavedFile;

interface Storage
{
    public function saveFile(UploadedFile $file, string $folderName): SavedFile;
}
