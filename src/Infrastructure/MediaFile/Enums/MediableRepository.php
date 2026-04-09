<?php

namespace Source\Infrastructure\MediaFile\Enums;

use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Infrastructure\Laravel\Traits\EnumFromNameTrait;

enum MediableRepository: string
{
    use EnumFromNameTrait;

    case Animal = AnimalRepository::class;
}
