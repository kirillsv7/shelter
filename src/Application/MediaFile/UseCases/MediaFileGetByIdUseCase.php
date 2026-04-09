<?php

namespace Source\Application\MediaFile\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\MediaFile\DTOs\MediaFileResponseDTO;
use Source\Application\MediaFile\Services\MediaFileService;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;

final class MediaFileGetByIdUseCase
{
    final public function __construct(
        protected MediaFileRepository $repository,
        protected MediaFileService $mediaFileService,
    ) {
    }

    public function apply(
        UuidInterface $id
    ): MediaFileResponseDTO {
        $mediaFile = $this->repository->getById($id);

        $urls[] = $this->mediaFileService->getUrl(
            $mediaFile->storageInfo->route,
            $mediaFile->storageInfo->fileName,
        );

        foreach ($mediaFile->sizes() as $size) {
            $urls[] = $this->mediaFileService->getUrl(
                $mediaFile->storageInfo->route,
                $size,
            );
        }

        return new MediaFileResponseDTO(
            mediaFileDTO: new MediaFileDTO(
                mediaFile: $mediaFile,
                urls: $urls,
            )
        );
    }
}
