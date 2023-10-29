<?php

namespace Source\Domain\MediaFile\Repositories;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Exceptions\MediaFileNotFoundException;

interface MediaFileRepository
{
    /**
     * @throws MediaFileNotFoundException
     */
    public function getById(UuidInterface $id): MediaFile;

    public function create(MediaFile $mediaFile): void;

    public function update(UuidInterface $id, MediaFile $mediaFile): void;
}
