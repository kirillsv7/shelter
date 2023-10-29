<?php

namespace Source\Domain\MediaFile\Aggregates;

use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class StorageInfo
{
    protected function __construct(
        public StringValueObject $disk,
        public StringValueObject $route,
        public StringValueObject $fileName,
    ) {
    }

    public static function make(
        StringValueObject $disk,
        StringValueObject $route,
        StringValueObject $fileName,
    ): self {
        return new self(
            disk: $disk,
            route: $route,
            fileName: $fileName
        );
    }

    public function toArray(): array
    {
        return [
            'disk' => $this->disk->value(),
            'route' => $this->route->value(),
            'fileName' => $this->fileName->value(),
        ];
    }
}
