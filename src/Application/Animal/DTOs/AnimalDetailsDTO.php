<?php

namespace Source\Application\Animal\DTOs;

use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Slug\DTOs\SlugDTO;

final readonly class AnimalDetailsDTO
{
    /**
     * @param  MediaFileDTO[]  $mediaFiles
     * @param  AnimalStatusUpdateDTO[]  $animalStatusUpdates
     */
    public function __construct(
        public AnimalDTO $animal,
        public SlugDTO $slug,
        public ?array $mediaFiles = [],
        public ?array $animalStatusUpdates = [],
    ) {
    }
}
