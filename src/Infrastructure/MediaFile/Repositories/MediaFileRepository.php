<?php

namespace Source\Infrastructure\MediaFile\Repositories;

use Illuminate\Database\ConnectionInterface;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository as MediaFileRepositoryContract;
use Source\Infrastructure\MediaFile\Models\MediaFileModel;

class MediaFileRepository implements MediaFileRepositoryContract
{
    public function __construct(
        protected ConnectionInterface $connection
    ) {
    }

    public function create(MediaFile $mediaFile): void
    {
        $model = new MediaFileModel();

        $this->connection->transaction(function () use ($model, $mediaFile) {
            $model->id = $mediaFile->id();
            $model->disk = $mediaFile->disk();
            $model->path = $mediaFile->path();
            $model->mediable_type = $mediaFile->mediableType();
            $model->mediable_id = $mediaFile->mediableId();

            $model->save();
        });
    }
}
