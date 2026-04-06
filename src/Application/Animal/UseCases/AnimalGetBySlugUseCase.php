<?php

namespace Source\Application\Animal\UseCases;

use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalResponseDTO;
use Source\Application\Animal\DTOs\AnimalStatusUpdateDTO;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\AnimalStatusUpdate;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusUpdateRepository;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Repositories\SlugRepository;

final class AnimalGetBySlugUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusUpdateRepository $animalStatusUpdateRepository,
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

        $mediaFiles = $this->mediaFileRepository->getByMediableUuid($animal->id());

        $slug = $this->slugRepository->getBySluggableUuid($animal->id());

        $animalStatusUpdates = $this->animalStatusUpdateRepository->getByAnimalId($animal->id());

        return new AnimalResponseDTO(
            animal: new AnimalDetailsDTO(
                animal: new AnimalDTO($animal),
                slug: new SlugDTO($slug),
                mediaFiles: array_map(
                    fn (MediaFile $mediaFile) => new MediaFileDTO($mediaFile),
                    $mediaFiles,
                ),
                animalStatusUpdates: array_map(
                    fn (AnimalStatusUpdate $animalStatusUpdate) => new AnimalStatusUpdateDTO($animalStatusUpdate),
                    $animalStatusUpdates,
                ),
            )
        );
    }
}
