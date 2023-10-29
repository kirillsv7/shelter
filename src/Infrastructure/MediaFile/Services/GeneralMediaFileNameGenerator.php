<?php

namespace Source\Infrastructure\MediaFile\Services;

use Illuminate\Http\UploadedFile;

final class GeneralMediaFileNameGenerator implements MediaFileNameGenerator
{
    public function __invoke(UploadedFile $uploadedFile): string
    {
        return 'original.' . $uploadedFile->extension();
    }
}
