<?php

namespace Source\Interface\MediaFile\DTOs;

use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Enums\MediableModel;

final readonly class MediaFileStoreRequestDTO
{
    public function __construct(
        public MediableModel $model,
        public UuidInterface $id,
        public UploadedFile $file,
    ) {
    }
}
