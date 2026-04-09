<?php

namespace Source\Application\Animal\DTOs;

use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Slug\DTOs\SlugDTO;

final readonly class AnimalDetailsDTO
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
}
