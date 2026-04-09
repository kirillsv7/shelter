<?php

namespace Source\Application\MediaFile\DTOs;

use JsonSerializable;

final readonly class MediaFileResponseDTO implements JsonSerializable
{
    public function __construct(
        protected MediaFileDTO $mediaFileDTO,
    ) {
    }

    public function jsonSerialize(): MediaFileDTO
    {
        return $this->mediaFileDTO;
    }
}
