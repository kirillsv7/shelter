<?php

namespace Source\Domain\MediaFile\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Source\Domain\Shared\ValueObjects\PathValueObject;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class StorageInfo implements Arrayable
{
    public function __construct(
        public StringValueObject $disk,
        public PathValueObject $route,
        public StringValueObject $fileName,
    ) {
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
