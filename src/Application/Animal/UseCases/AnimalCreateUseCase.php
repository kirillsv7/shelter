<?php

namespace Source\Application\Animal\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Source\Application\Animal\DTOs\AnimalDetailsDTO;
use Source\Application\Animal\DTOs\AnimalDTO;
use Source\Application\Animal\DTOs\AnimalResponseDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Animal\ValueObjects\AnimalInfo;
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
            sluggableType: new AnimalModel(),
            sluggableId: $animal->id,
        );

        $this->animalRepository->create($animal);

        $this->slugRepository->create($slug);

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return new AnimalResponseDTO(
            animal: new AnimalDetailsDTO(
                animal: new AnimalDTO($animal),
                slug: new SlugDTO($slug),
            )
        );
    }
}
