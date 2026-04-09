<?php

namespace Source\Infrastructure\MediaFile\Repositories;

use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Exceptions\MediaFileNotFoundException;
use Source\Domain\MediaFile\Repositories\MediaFileRepository as MediaFileRepositoryContract;
use Source\Infrastructure\MediaFile\Mappers\MediaFileMapper;
use Source\Infrastructure\MediaFile\Models\MediaFileModel;

final class MediaFileRepository implements MediaFileRepositoryContract
{
    public function __construct(
        protected ConnectionInterface $connection,
        protected MediaFileMapper $mapper,
    ) {
    }

    public function getById(UuidInterface $id): MediaFile
    {
        /** @var ?MediaFileModel $model */
        $model = MediaFileModel::query()->find($id);

        if (!$model) {
            throw new MediaFileNotFoundException();
        }

        return $this->mapper->modelToEntity($model);
    }

    public function getByMediableUuid(UuidInterface $id): array
    {
        return MediaFileModel::query()
            ->where('mediable_id', $id)
            ->get()
            ->map(fn (MediaFileModel $model) => $this->mapper->modelToEntity($model))
            ->toArray();
    }

    public function getByMediableUuids(array $ids): array
    {
        return MediaFileModel::query()
            ->whereIn('mediable_id', $ids)
            ->get()
            ->map(fn (MediaFileModel $model) => $this->mapper->modelToEntity($model))
            ->toArray();
    }

    public function create(MediaFile $mediaFile): void
    {
        $this->connection->transaction(function () use ($mediaFile) {
            $model = $this->mapper->entityToModel($mediaFile);

            $model->setAttribute('id', $mediaFile->id);
            $model->setAttribute('created_at', CarbonImmutable::now());

            $model->save();
        });
    }

    public function update(UuidInterface $id, MediaFile $mediaFile): void
    {
        /** @var MediaFileModel $model */
        $model = MediaFileModel::query()->find($id);

        $this->connection->transaction(function () use ($mediaFile, $model) {
            $model = $this->mapper->entityToModel($mediaFile, $model);

            $model->setAttribute('updated_at', CarbonImmutable::now());

            $model->save();
        });
    }
}
