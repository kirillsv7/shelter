<?php

namespace Source\Infrastructure\MediaFile\Services;

use Illuminate\Http\UploadedFile;
use Source\Domain\MediaFile\Contracts\MediaFileNameGenerator;

final class GeneralMediaFileNameGenerator implements MediaFileNameGenerator
{
    public function __invoke(UploadedFile $uploadedFile): string
    {
        return 'original.' . $uploadedFile->extension();
    }
}
