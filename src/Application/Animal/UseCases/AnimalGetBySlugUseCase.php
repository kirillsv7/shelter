<?php

namespace Source\Application\Animal\UseCases;

use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalResponseDTO;
use Source\Application\Animal\DTOs\AnimalStatusDTO;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\AnimalStatus;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusRepository;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Repositories\SlugRepository;

final class AnimalGetBySlugUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusRepository $animalStatusRepository,
        protected MediaFileRepository $mediaFileRepository,
        protected SlugRepository $slugRepository,
    ) {
    }

    public function apply(
        AnimalType $type,
        StringValueObject $slug,
    ): AnimalResponseDTO {
        $animal = $this->animalRepository->getBySlug(
            type: $type,
            slug: $slug,
        );

        $mediaFiles = $this->mediaFileRepository->getByMediableUuid($animal->id);

        $slug = $this->slugRepository->getBySluggableUuid($animal->id);

        $animalStatuses = $this->animalStatusRepository->getByAnimalId($animal->id);

        return new AnimalResponseDTO(
            animal: new AnimalDetailsDTO(
                animalDTO: new AnimalDTO($animal),
                slugDTO: new SlugDTO($slug),
                mediaFileDTOs: array_map(
                    fn (MediaFile $mediaFile) => new MediaFileDTO($mediaFile),
                    $mediaFiles,
                ),
                animalStatusDTOs: array_map(
                    fn (AnimalStatus $animalStatus) => new AnimalStatusDTO($animalStatus),
                    $animalStatuses,
                ),
            )
        );
    }
}
