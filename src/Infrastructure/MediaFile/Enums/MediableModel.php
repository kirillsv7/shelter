<?php

namespace Source\Infrastructure\MediaFile\Enums;

use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Laravel\Traits\EnumFromNameTrait;

enum MediableModel: string
{
    use EnumFromNameTrait;

    case Animal = AnimalModel::class;
}
