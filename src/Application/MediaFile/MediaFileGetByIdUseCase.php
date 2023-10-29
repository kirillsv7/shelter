<?php

namespace Source\Application\MediaFile;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;

final class MediaFileGetByIdUseCase
{
    final public function __construct(
        protected MediaFileRepository $repository
    ) {
    }

    public function apply(
        UuidInterface $id
    ): MediaFile {
        return $this->repository->getById($id);
    }
}
