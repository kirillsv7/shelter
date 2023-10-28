<?php

namespace Source\Infrastructure\MediaFile\Services;

use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\UuidInterface;
use Source\Infrastructure\Laravel\Models\BaseModel;

interface MediaFilePathGenerator
{
    public function __invoke(
        BaseModel $mediableModel,
        UuidInterface $mediableId,
        UploadedFile $uploadedFile
    ): string;
}
