<?php

namespace Source\Domain\MediaFile\Enums;

use Source\Infrastructure\Animal\Models\AnimalModel;

enum MediableModel: string
{
    case Animal = AnimalModel::class;

    public static function fromName(string $modelName): self
    {
        foreach (self::cases() as $modelCase) {
            if ($modelCase->name === $modelName) {
                return $modelCase;
            }
        }

        throw new \ValueError(
            sprintf('%s is not a valid backing value for enum %s', $modelName, self::class)
        );
    }
}
