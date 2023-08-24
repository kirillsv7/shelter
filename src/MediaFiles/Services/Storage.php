<?php

namespace Source\MediaFiles\Services;

use Illuminate\Http\UploadedFile;
use Source\MediaFiles\DTOs\SavedFileDTO;

interface Storage
{
    public function saveFile(UploadedFile $file, string $folderName): SavedFileDTO;
}
