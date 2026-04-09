<?php

namespace Source\Application\MediaFile\DTOs;

use JsonSerializable;
use Source\Domain\MediaFile\Aggregates\MediaFile;

final readonly class MediaFileDTO implements JsonSerializable
{
    public function __construct(
        protected MediaFile $mediaFile,
        protected ?array $urls = null,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->mediaFile->id,
            'storage_info' => $this->mediaFile->storageInfo->toArray(),
            'sizes' => $this->mediaFile->sizes(),
            'mimetype' => $this->mediaFile->mimetype->value(),
            'mediableType' => $this->mediaFile->mediableType,
            'mediableId' => $this->mediaFile->mediableId,
            'created_at' => $this->mediaFile->createdAt,
            'updated_at' => $this->mediaFile->updatedAt,
            'urls' => $this->urls,
        ];
    }
}
