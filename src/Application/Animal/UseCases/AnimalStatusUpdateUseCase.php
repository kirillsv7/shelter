<?php

namespace Source\Application\Animal\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\UseCases\Traits\LoadSlugTrait;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalStatusUpdate;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusUpdateRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Interface\Animal\DTOs\AnimalStatusUpdateRequestDTO;

final class AnimalStatusUpdateUseCase
{
    use LoadSlugTrait;

    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusUpdateRepository $animalStatusUpdateRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(
        UuidInterface $id,
        AnimalStatusUpdateRequestDTO $dto,
    ): Animal {
        $animal = $this->animalRepository->getById($id);

        $statusUpdated = $animal->statusUpdate($dto->status);

        $this->animalRepository->update(
            id: $id,
            animal: $animal,
        );

        if ($statusUpdated) {
            $this->createAnimalStatusUpdate($animal, $dto);
        }

        $this->loadSlug(
            $animal,
            StringValueObject::fromString(AnimalModel::class),
        );

        $this->dispatcher->multiDispatch(
            $animal->releaseEvents(),
        );

        return $animal;
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
