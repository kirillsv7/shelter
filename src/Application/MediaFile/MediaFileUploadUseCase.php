<?php

namespace Source\Application\MediaFile;

use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\MediaFile\Contracts\Storage;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class MediaFileUploadUseCase
{
    public function __construct(
        protected Storage $storage,
        protected MediaFileRepository $repository,
        protected MultiDispatcher $dispatcher
    ) {
    }

    public function upload(
        UploadedFile $file,
        string $filePath,
        BaseModel $model,
        string $id
    ): MediaFile {

        $savedFile = $this->storage->saveFile(
            file: $file,
            filePath: $filePath
        );

        $mediaFile = MediaFile::create(
            id: Uuid::uuid4(),
            disk: $savedFile->disk,
            path: $savedFile->path,
            mediableType: $model,
            mediableId: Uuid::fromString($id)
        );

        $this->repository->create($mediaFile);

        $this->dispatcher->multiDispatch($mediaFile->releaseEvents());

        return $mediaFile;
    }


}
