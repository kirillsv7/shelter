<?php

namespace Source\Application\Animal\UseCases;

use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalListResponseDTO;
use Source\Application\Animal\DTOs\AnimalStatusDTO;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Shared\DTOs\PaginationDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalStatus;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusRepository;
use Source\Domain\Animal\Search\AnimalSearchCriteria;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Interface\Animal\DTOs\AnimalIndexRequestDTO;

final class AnimalIndexUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusRepository $animalStatusRepository,
        protected MediaFileRepository $mediaFileRepository,
        protected SlugRepository $slugRepository,
    ) {
    }

    public function apply(AnimalIndexRequestDTO $dto): AnimalListResponseDTO
    {
        // TODO: Decouple pagination from repository
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
            fn (Animal $animal) => $animal->id,
            $animals,
        );

        $mediaFiles = $this->mediaFileRepository->getByMediableUuids($animalIds);

        $slugs = $this->slugRepository->getBySluggableUuids($animalIds);

        $animalStatuses = $this->animalStatusRepository->getByAnimalIds($animalIds);

        $animalsTotalCount = $this->animalRepository->totalCountByCriteria($criteria);

        $pagination->generateLinks($animalsTotalCount);

        $animalResponseDTOs = [];

        foreach ($animals as $animal) {
            $animalMediaFiles = array_filter(
                $mediaFiles,
                fn (MediaFile $mediaFile) => $mediaFile->mediableId->equals($animal->id),
            );

            $animalStatuses = array_filter(
                $animalStatuses,
                fn (AnimalStatus $animalStatus) => $animalStatus->animalId->equals($animal->id),
            );

            $animalResponseDTOs[] = new AnimalDetailsDTO(
                animalDTO: new AnimalDTO($animal),
                slugDTO: new SlugDTO(
                    array_find(
                        $slugs,
                        fn (Slug $slug) => $slug->sluggableId->equals($animal->id),
                    )
                ),
                mediaFileDTOs: array_map(
                    fn (MediaFile $mediaFile) => new MediaFileDTO($mediaFile),
                    $animalMediaFiles,
                ),
                animalStatusDTOs: array_map(
                    fn (AnimalStatus $animalStatus) => new AnimalStatusDTO($animalStatus),
                    $animalStatuses,
                ),
            );
        }

        return new AnimalListResponseDTO(
            animals: $animalResponseDTOs,
            pagination: new PaginationDTO($pagination),
        );
    }
}
