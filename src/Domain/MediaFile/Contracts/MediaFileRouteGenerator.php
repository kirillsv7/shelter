<?php

namespace Source\Domain\MediaFile\Contracts;

use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\UuidInterface;
use Source\Infrastructure\MediaFile\Enums\MediableFolder;

interface MediaFileRouteGenerator
{
    public function __invoke(
        MediableFolder $mediableFolder,
        UuidInterface $mediableId,
        UploadedFile $uploadedFile,
    ): string;
}
