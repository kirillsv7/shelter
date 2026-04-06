<?php

namespace Source\Application\Animal\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalResponseDTO;
use Source\Application\Animal\DTOs\AnimalStatusDTO;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalStatus;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusRepository;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Interface\Animal\DTOs\AnimalUpdateRequestDTO;

final class AnimalUpdateUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusRepository $animalStatusRepository,
        protected MediaFileRepository $mediaFileRepository,
        protected SlugRepository $slugRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(
        UuidInterface $id,
        AnimalUpdateRequestDTO $dto,
    ): AnimalResponseDTO {
        $animal = $this->animalRepository->getById($id);

        $animalInfo = $animal->info->change(
            $dto->name,
            $dto->type,
            $dto->gender,
            $dto->breed,
            $dto->birthdate,
            $dto->entrydate,
        );

        $animal = Animal::make(
            id: $id,
            info: $animalInfo,
            status: $animal->status(),
            published: $dto->published,
            createdAt: $animal->createdAt,
            updatedAt: CarbonImmutable::now(),
        );

        $statusUpdated = $animal->statusUpdate($dto->status);

        $this->animalRepository->update(
            id: $id,
            animal: $animal,
        );

        if ($statusUpdated) {
            $this->createAnimalStatusUpdate($animal, $dto);
        }

        $this->dispatcher->multiDispatch($animal->releaseEvents());

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

    protected function createAnimalStatusUpdate(
        Animal $animal,
        AnimalUpdateRequestDTO $dto,
    ): void {
        $animalStatus = AnimalStatus::create(
            id: Uuid::uuid7(),
            animalId: $animal->id,
            status: $dto->status,
            notes: $dto->notes,
            createdAt: CarbonImmutable::now(),
        );

        $this->animalStatusRepository->create($animalStatus);
    }
}
