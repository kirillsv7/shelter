<?php

namespace Source\Domain\Organization\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class Socials implements Arrayable
{
    public function __construct(
        public ?StringValueObject $facebook,
        public ?StringValueObject $instagram,
        public ?StringValueObject $twitter,
        public ?StringValueObject $youtube,
        public ?StringValueObject $tiktok,
    ) {
    }

    public function toArray(): array
    {
        return [
            'facebook' => $this->facebook?->value(),
            'instagram' => $this->instagram?->value(),
            'twitter' => $this->twitter?->value(),
            'youtube' => $this->youtube?->value(),
            'tiktok' => $this->tiktok?->value(),
        ];
    }
}
