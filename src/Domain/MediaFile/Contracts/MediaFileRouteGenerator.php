<?php

namespace Source\Domain\MediaFile\Contracts;

use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\UuidInterface;
use Source\Infrastructure\Laravel\Models\BaseModel;

interface MediaFileRouteGenerator
{
    public function __invoke(
        BaseModel $mediableModel,
        UuidInterface $mediableId,
        UploadedFile $uploadedFile
    ): string;
}
