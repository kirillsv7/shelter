<?php

namespace Source\Infrastructure\MediaFile\Mappers;

use Ramsey\Uuid\Uuid;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Aggregates\StorageInfo;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\MediaFile\Models\MediaFileModel;

final readonly class MediaFileMapper
{
    public function modelToEntity(MediaFileModel $model): MediaFile
    {
        return MediaFile::make(
            id: Uuid::fromString($model->id),
            storageInfo: StorageInfo::make(
                disk: StringValueObject::fromString($model->getAttribute('storage_info')['disk']),
                route: StringValueObject::fromString($model->getAttribute('storage_info')['route']),
                fileName: StringValueObject::fromString($model->getAttribute('storage_info')['fileName']),
            ),
            sizes: $model->getAttribute('sizes'),
            mimetype: StringValueObject::fromString($model->getAttribute('mimetype')),
            mediableType: StringValueObject::fromString($model->getAttribute('mediable_type')),
            mediableId: Uuid::fromString($model->getAttribute('mediable_id')),
            createdAt: $model->getAttribute('created_at'),
            updatedAt: $model->getAttribute('updated_at'),
        );
    }

    public function entityToModel(
        MediaFile $mediaFile,
        ?MediaFileModel $model = null
    ): MediaFileModel {
        if (null === $model) {
            $model = new MediaFileModel();
        }

        $model->setAttribute('storage_info', $mediaFile->storageInfo->toArray());
        $model->setAttribute('sizes', $mediaFile->sizes());
        $model->setAttribute('mimetype', $mediaFile->mimetype);
        $model->setAttribute('mediable_type', $mediaFile->mediableType);
        $model->setAttribute('mediable_id', $mediaFile->mediableId);

        return $model;
    }
}
