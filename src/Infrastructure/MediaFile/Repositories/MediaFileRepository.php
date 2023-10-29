<?php

namespace Source\Infrastructure\MediaFile\Repositories;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Aggregates\StorageInfo;
use Source\Domain\MediaFile\Exceptions\MediaFileNotFoundException;
use Source\Domain\MediaFile\Repositories\MediaFileRepository as MediaFileRepositoryContract;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\MediaFile\Models\MediaFileModel;

final class MediaFileRepository implements MediaFileRepositoryContract
{
    public function __construct(
        protected ConnectionInterface $connection
    ) {
    }

    public function getById(UuidInterface $id): MediaFile
    {
        /** @var ?MediaFileModel $model */
        $model = MediaFileModel::query()->find($id);

        if (!$model) {
            throw new MediaFileNotFoundException('MediaFile doesn\'t exists');
        }

        return self::map($model);
    }

    public function create(MediaFile $mediaFile): void
    {
        $model = new MediaFileModel();

        $this->connection->transaction(function () use ($model, $mediaFile) {
            $model->id = $mediaFile->id;
            $model->storage_info = $mediaFile->storageInfo->toArray();
            $model->sizes = $mediaFile->sizes();
            $model->mimetype = $mediaFile->mimetype;
            $model->mediable_type = $mediaFile->mediableType();
            $model->mediable_id = $mediaFile->mediableId;
            $model->created_at = Carbon::now();

            $model->save();
        });
    }

    public function update(UuidInterface $id, MediaFile $mediaFile): void
    {
        /** @var MediaFileModel $model */
        $model = MediaFileModel::query()->find($id);

        $this->connection->transaction(function () use ($model, $mediaFile) {
            $model->storage_info = $mediaFile->storageInfo->toArray();
            $model->sizes = $mediaFile->sizes();
            $model->mimetype = $mediaFile->mimetype;
            $model->mediable_type = $mediaFile->mediableType();
            $model->mediable_id = $mediaFile->mediableId;
            $model->updated_at = Carbon::now();

            $model->save();
        });
    }

    public static function map(MediaFileModel $model): MediaFile
    {
        return MediaFile::make(
            id: Uuid::fromString($model->id),
            storageInfo: StorageInfo::make(
                disk: StringValueObject::fromString($model->storage_info['disk']),
                route: StringValueObject::fromString($model->storage_info['route']),
                fileName: StringValueObject::fromString($model->storage_info['fileName'])
            ),
            sizes: $model->sizes,
            mimetype: StringValueObject::fromString($model->mimetype),
            mediableType: new $model->mediable_type(),
            mediableId: Uuid::fromString($model->mediable_id),
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
        );
    }
}
