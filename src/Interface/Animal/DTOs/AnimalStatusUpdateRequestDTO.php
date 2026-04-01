<?php

namespace Source\Interface\Animal\DTOs;

use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class AnimalStatusUpdateRequestDTO
{
    public function __construct(
        public AnimalStatus $status,
        public ?StringValueObject $notes,
    ) {
    }
}
