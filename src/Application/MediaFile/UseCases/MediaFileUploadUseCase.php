<?php

namespace Source\Application\MediaFile\UseCases;

use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\MediaFile\DTOs\MediaFileResponseDTO;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Aggregates\StorageInfo;
use Source\Domain\MediaFile\Contracts\MediaFileNameGenerator;
use Source\Domain\MediaFile\Contracts\MediaFileRouteGenerator;
use Source\Domain\MediaFile\Contracts\Storage;
use Source\Domain\MediaFile\Exceptions\MediableNotFoundException;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Infrastructure\MediaFile\Enums\MediableFolder;
use Source\Infrastructure\MediaFile\Enums\MediableRepository;
use Source\Infrastructure\MediaFile\Repositories\MediableRepository as MediableRepositoryContract;
use Source\Interface\MediaFile\DTOs\MediaFileStoreRequestDTO;

final class MediaFileUploadUseCase
{
    public function __construct(
        protected Storage $storage,
        protected MediaFileNameGenerator $mediaFileNameGenerator,
        protected MediaFileRepository $repository,
        protected MediaFileRouteGenerator $mediaFileRouteGenerator,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function upload(
        MediaFileStoreRequestDTO $dto,
    ): MediaFileResponseDTO {
        $mediableRepositoryEnum = MediableRepository::fromName($dto->model->name);

        /** @var MediableRepositoryContract $mediableRepository */
        $mediableRepository = app($mediableRepositoryEnum->value);

        if (false === $mediableRepository->exists($dto->id)) {
            throw new MediableNotFoundException();
        }

        $fileRoute = $this->mediaFileRouteGenerator->__invoke(
            MediableFolder::fromName($dto->model->name),
            $dto->id,
            $dto->file,
        );

        $fileName = $this->mediaFileNameGenerator->__invoke($dto->file);

        $savedFile = $this->storage->saveFile(
            file: $dto->file,
            fileRoute: $fileRoute,
            fileName: $fileName,
        );

        $mediaFile = MediaFile::create(
            id: Uuid::uuid7(),
            storageInfo: StorageInfo::make(
                disk: StringValueObject::fromString($savedFile->disk),
                route: StringValueObject::fromString($savedFile->route),
                fileName: StringValueObject::fromString($savedFile->name),
            ),
            sizes: [],
            mimetype: StringValueObject::fromString($dto->file->getMimeType()),
            mediableType: StringValueObject::fromString($dto->model->value),
            mediableId: $dto->id,
            createdAt: Carbon::now(),
        );

        $this->repository->create($mediaFile);

        $this->dispatcher->multiDispatch($mediaFile->releaseEvents());

        return new MediaFileResponseDTO(
            mediaFileDTO: new MediaFileDTO(
                mediaFile: $mediaFile,
            )
        );
    }
}
