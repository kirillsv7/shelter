<?php

namespace Source\Infrastructure\MediaFile\Enums;

use Source\Infrastructure\Laravel\Traits\EnumFromNameTrait;

enum MediableFolder: string
{
    use EnumFromNameTrait;

    case Animal = 'animals';
}
