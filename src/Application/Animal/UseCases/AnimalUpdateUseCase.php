<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\UseCases\Traits\LoadSlugTrait;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Interface\Animal\DTOs\AnimalUpdateRequestDTO;

final class AnimalUpdateUseCase
{
    use LoadSlugTrait;

    public function __construct(
        protected AnimalRepository $repository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(
        UuidInterface $id,
        AnimalUpdateRequestDTO $dto,
    ): Animal {
        $animal = $this->repository->getById($id);

        $animal->info()->change(
            $dto->name,
            $dto->type,
            $dto->gender,
            $dto->breed,
            $dto->birthdate,
            $dto->entrydate,
        );

        $this->repository->update(
            id: $id,
            animal: $animal,
        );

        $this->loadSlug($animal);

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return $animal;
    }
}
