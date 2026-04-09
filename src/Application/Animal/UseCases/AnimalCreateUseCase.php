<?php

namespace Source\Application\Animal\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalResponseDTO;
use Source\Application\Animal\DTOs\AnimalStatusDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalStatus;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\Repositories\AnimalStatusRepository;
use Source\Domain\Animal\ValueObjects\AnimalInfo;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Domain\Slug\ValueObjects\SlugString;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Interface\Animal\DTOs\AnimalStoreRequestDTO;

final class AnimalCreateUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected AnimalStatusRepository $animalStatusRepository,
        protected SlugRepository $slugRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(AnimalStoreRequestDTO $dto): AnimalResponseDTO
    {
        $animal = Animal::create(
            id: Uuid::uuid7(),
            info: new AnimalInfo(
                name: $dto->name,
                type: $dto->type,
                gender: $dto->gender,
                breed: $dto->breed,
                birthdate: $dto->birthdate,
                entrydate: $dto->entrydate,
            ),
            status: $dto->status,
            published: $dto->published,
            createdAt: CarbonImmutable::now(),
        );

        $slugParts = [
            $dto->name,
            $dto->type->value,
            $dto->gender->value,
            $dto->breed,
        ];

        $slug = Slug::create(
            id: Uuid::uuid7(),
            value: SlugString::fromArray($slugParts),
            sluggableType: StringValueObject::fromString(AnimalModel::class),
            sluggableId: $animal->id,
        );

        $animalStatus = AnimalStatus::create(
            id: Uuid::uuid7(),
            animalId: $animal->id,
            status: $dto->status,
            notes: $dto->notes,
            createdAt: CarbonImmutable::now(),
        );

        $this->animalRepository->create($animal);

        $this->slugRepository->create($slug);

        $this->animalStatusRepository->create($animalStatus);

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return new AnimalResponseDTO(
            animal: new AnimalDetailsDTO(
                animal: new AnimalDTO($animal),
                slug: new SlugDTO($slug),
                animalStatuses: [
                    new AnimalStatusDTO($animalStatus),
                ]
            )
        );
    }
}
