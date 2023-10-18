<?php

namespace Source\Domain\MediaFile\Repositories;

use Source\Domain\MediaFile\Aggregates\MediaFile;

interface MediaFileRepository
{
    public function create(MediaFile $mediaFile): int;
}
