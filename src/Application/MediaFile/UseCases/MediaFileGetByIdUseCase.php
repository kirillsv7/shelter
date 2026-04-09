<?php

namespace Source\Application\MediaFile\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\MediaFile\DTOs\MediaFileResponseDTO;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;

final class MediaFileGetByIdUseCase
{
    final public function __construct(
        protected MediaFileRepository $repository
    ) {
    }

    public function apply(
        UuidInterface $id
    ): MediaFileResponseDTO {
        $mediaFile = $this->repository->getById($id);

        return new MediaFileResponseDTO(
            mediaFileDTO: new MediaFileDTO(
                mediaFile: $mediaFile,
            )
        );
    }
}
