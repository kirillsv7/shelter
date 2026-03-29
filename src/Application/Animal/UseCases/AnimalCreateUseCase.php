<?php

namespace Source\Application\Animal\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalInfo;
use Source\Domain\Animal\Repositories\AnimalRepository;
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
        protected SlugRepository $slugRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(
        AnimalStoreRequestDTO $dto,
        StringValueObject $dateTimeFormat,
    ): Animal {
        $animal = Animal::create(
            id: Uuid::uuid4(),
            info: AnimalInfo::create(
                name: $dto->name,
                type: $dto->type,
                gender: $dto->gender,
                breed: $dto->breed,
                birthdate: $dto->birthdate,
                entrydate: $dto->entrydate,
                dateTimeFormat: $dateTimeFormat,
            ),
            createdAt: CarbonImmutable::now(),
        );

        $this->animalRepository->create($animal);

        $slugParts = [
            $dto->name,
            $dto->type->value,
            $dto->gender->value,
            $dto->breed,
        ];

        $slug = Slug::create(
            id: Uuid::uuid4(),
            value: SlugString::fromArray($slugParts),
            sluggableType: new AnimalModel(),
            sluggableId: $animal->id(),
        );

        $this->slugRepository->create($slug);

        $animal->addSlug($slug->value());

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return $animal;
    }
}
