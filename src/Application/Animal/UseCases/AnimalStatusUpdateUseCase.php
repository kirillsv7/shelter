<?php

namespace Source\Application\Animal\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalStatusUpdateDTO;
use Source\Application\MediaFile\DTOs\MediaFileDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalStatusUpdate;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusUpdateRepository;
use Source\Domain\MediaFile\Aggregates\MediaFile;
use Source\Domain\MediaFile\Repositories\MediaFileRepository;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Interface\Animal\DTOs\AnimalResponseDTO;
use Source\Interface\Animal\DTOs\AnimalStatusUpdateRequestDTO;

final class AnimalStatusUpdateUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusUpdateRepository $animalStatusUpdateRepository,
        protected MediaFileRepository $mediaFileRepository,
        protected SlugRepository $slugRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(
        UuidInterface $id,
        AnimalStatusUpdateRequestDTO $dto,
    ): AnimalResponseDTO {
        $animal = $this->animalRepository->getById($id);

        $statusUpdated = $animal->statusUpdate($dto->status);

        if ($statusUpdated) {
            $this->animalRepository->update(
                id: $id,
                animal: $animal,
            );

            $this->createAnimalStatusUpdate($animal, $dto);
        }

        $this->dispatcher->multiDispatch(
            $animal->releaseEvents(),
        );

        $mediaFiles = $this->mediaFileRepository->getByMediableUuid($id);

        $slug = $this->slugRepository->getBySluggableUuid($id);

        $animalStatusUpdates = $this->animalStatusUpdateRepository->getByAnimalId($id);

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

    protected function createAnimalStatusUpdate(
        Animal $animal,
        AnimalStatusUpdateRequestDTO $dto,
    ): void {
        $animalStatusUpdate = AnimalStatusUpdate::create(
            id: Uuid::uuid7(),
            animalId: $animal->id(),
            status: $dto->status,
            notes: $dto->notes,
            createdAt: CarbonImmutable::now(),
        );


        $this->animalStatusUpdateRepository->create($animalStatusUpdate);
    }
}
