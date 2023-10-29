<?php

namespace Source\Application\MediaFile;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Aggregates\StorageInfo;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\MediaFile\Contracts\Storage;
use Source\Domain\Shared\ValueObjects\StringValueObject;
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
        UploadedFile $uploadedFile,
        string $fileRoute,
        string $fileName,
        BaseModel $mediableType,
        UuidInterface $mediableId
    ): MediaFile {
        $savedFile = $this->storage->saveFile(
            file: $uploadedFile,
            fileRoute: $fileRoute,
            fileName: $fileName
        );

        $mediaFile = MediaFile::create(
            id: Uuid::uuid4(),
            storageInfo: StorageInfo::make(
                disk: StringValueObject::fromString($savedFile->disk),
                route: StringValueObject::fromString($savedFile->route),
                fileName: StringValueObject::fromString($savedFile->name),
            ),
            sizes: [],
            mimetype: StringValueObject::fromString($uploadedFile->getMimeType()),
            mediableType: $mediableType,
            mediableId: $mediableId,
            createdAt: Carbon::now()
        );

        $this->repository->create($mediaFile);

        $this->dispatcher->multiDispatch($mediaFile->releaseEvents());

        return $mediaFile;
    }


}
