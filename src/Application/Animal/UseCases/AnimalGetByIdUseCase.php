<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalResponseDTO;
use Source\Application\Animal\DTOs\AnimalStatusDTO;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\AnimalStatus;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusRepository;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\Slug\Repositories\SlugRepository;

final class AnimalGetByIdUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusRepository $animalStatusRepository,
        protected MediaFileRepository $mediaFileRepository,
        protected SlugRepository $slugRepository,
    ) {
    }

    public function apply(UuidInterface $id): AnimalResponseDTO
    {
        $animal = $this->animalRepository->getById($id);

        $mediaFiles = $this->mediaFileRepository->getByMediableUuid($id);

        $slug = $this->slugRepository->getBySluggableUuid($id);

        $animalStatuses = $this->animalStatusRepository->getByAnimalId($id);

        return new AnimalResponseDTO(
            animal: new AnimalDetailsDTO(
                animal: new AnimalDTO($animal),
                slug: new SlugDTO($slug),
                mediaFiles: array_map(
                    fn (MediaFile $mediaFile) => new MediaFileDTO($mediaFile),
                    $mediaFiles,
                ),
                animalStatuses: array_map(
                    fn (AnimalStatus $animalStatus) => new AnimalStatusDTO($animalStatus),
                    $animalStatuses,
                ),
            )
        );
    }
}
