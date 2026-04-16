<?php

namespace Source\Application\Animal\DTOs;

use JsonSerializable;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Slug\DTOs\SlugDTO;

final readonly class AnimalDetailsDTO implements JsonSerializable
{
    /**
     * @param  MediaFileDTO[]  $mediaFileDTOs
     * @param  AnimalStatusDTO[]  $animalStatusDTOs
     */
    public function __construct(
        public AnimalDTO $animalDTO,
        public SlugDTO $slugDTO,
        public ?array $mediaFileDTOs = [],
        public ?array $animalStatusDTOs = [],
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'animal' => $this->animalDTO,
            'slug' => $this->slugDTO,
            'mediaFiles' => $this->mediaFileDTOs,
            'animalStatuses' => $this->animalStatusDTOs,
        ];
    }
}
