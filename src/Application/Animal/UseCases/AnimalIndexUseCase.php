<?php

namespace Source\Application\Animal\UseCases;

use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalListResponseDTO;
use Source\Application\Animal\DTOs\AnimalStatusUpdateDTO;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Shared\DTOs\PaginationDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalStatusUpdate;
use Source\Domain\Animal\AnimalSearchCriteria;
use Source\Domain\Animal\Enums\AnimalGender;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusUpdateRepository;
use Source\Domain\Animal\ValueObjects\Name;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Interface\Animal\DTOs\AnimalIndexRequestDTO;

final class AnimalIndexUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusUpdateRepository $animalStatusUpdateRepository,
        protected MediaFileRepository $mediaFileRepository,
        protected SlugRepository $slugRepository,
    ) {
    }

    public function apply(AnimalIndexRequestDTO $dto): AnimalListResponseDTO
    {
        $criteria = AnimalSearchCriteria::create(
            $dto->name,
            $dto->type,
            $dto->gender,
            $dto->ageMin,
            $dto->ageMax,
        );

        $pagination = Pagination::create(
            $dto->limit,
            $dto->page,
        );

        $animals = $this->animalRepository->index(
            $criteria,
            $pagination,
        );

        $animalIds = array_map(
            fn(Animal $animal) => $animal->id,
            $animals,
        );

        $mediaFiles = $this->mediaFileRepository->getByMediableUuids($animalIds);

        $slugs = $this->slugRepository->getBySluggableUuids($animalIds);

        $animalStatusUpdates = $this->animalStatusUpdateRepository->getByAnimalIds($animalIds);

        $animalsTotalCount = $this->animalRepository->totalCountByCriteria($criteria);

        $pagination->generateLinks($animalsTotalCount);

        $animalResponseDTOs = [];

        foreach ($animals as $animal) {
            $animalMediaFiles = array_filter(
                $mediaFiles,
                fn(MediaFile $mediaFile) => $mediaFile->mediableId->equals($animal->id),
            );

            $animalStatusUpdates = array_filter(
                $animalStatusUpdates,
                fn(AnimalStatusUpdate $animalStatusUpdate) => $animalStatusUpdate->animalId->equals($animal->id),
            );

            $animalResponseDTOs[] = new AnimalDetailsDTO(
                animal: new AnimalDTO($animal),
                slug: new SlugDTO(
                    array_find(
                        $slugs,
                        fn(Slug $slug) => $slug->sluggableId()->equals($animal->id),
                    )
                ),
                mediaFiles: array_map(
                    fn(MediaFile $mediaFile) => new MediaFileDTO($mediaFile),
                    $animalMediaFiles,
                ),
                animalStatusUpdates: array_map(
                    fn(AnimalStatusUpdate $animalStatusUpdate) => new AnimalStatusUpdateDTO($animalStatusUpdate),
                    $animalStatusUpdates,
                ),
            );
        }

        return new AnimalListResponseDTO(
            animals: $animalResponseDTOs,
            pagination: new PaginationDTO($pagination),
        );
    }
}
