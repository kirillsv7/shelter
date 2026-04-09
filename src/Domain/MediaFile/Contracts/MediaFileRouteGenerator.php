<?php

namespace Source\Domain\MediaFile\Contracts;

use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\ValueObjects\StringValueObject;

interface MediaFileRouteGenerator
{
    public function __invoke(
        StringValueObject $mediableModel,
        UuidInterface $mediableId,
        UploadedFile $uploadedFile
    ): string;
}
