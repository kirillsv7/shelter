<?php

namespace Source\Application\Animal\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Aggregates\AnimalInfo;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Interface\Animal\DTOs\AnimalStoreRequestDTO;

final class AnimalCreateUseCase
{
    public function __construct(
        protected AnimalRepository $repository,
        protected MultiDispatcher $dispatcher
    ) {
    }

    public function apply(AnimalStoreRequestDTO $dto): Animal
    {
        $animal = Animal::create(
            id: Uuid::uuid4(),
            info: AnimalInfo::create(
                name: $dto->name,
                type: $dto->type,
                gender: $dto->gender,
                breed: $dto->breed,
                birthdate: $dto->birthdate,
                entrydate: $dto->entrydate,
            ),
            createdAt: CarbonImmutable::now(),
        );

        $this->repository->create($animal);

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return $animal;
    }
}
