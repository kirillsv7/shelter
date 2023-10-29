<?php

namespace Source\Infrastructure\MediaFile\Services;

use Illuminate\Http\UploadedFile;

interface MediaFileNameGenerator
{
    public function __invoke(UploadedFile $uploadedFile): string;
}
