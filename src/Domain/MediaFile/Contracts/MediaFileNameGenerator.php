<?php

namespace Source\Domain\MediaFile\Contracts;

use Illuminate\Http\UploadedFile;

interface MediaFileNameGenerator
{
    public function __invoke(UploadedFile $uploadedFile): string;
}
