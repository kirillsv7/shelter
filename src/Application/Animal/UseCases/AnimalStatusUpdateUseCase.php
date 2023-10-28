<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;

final class AnimalStatusUpdateUseCase
{
    public function __construct(
        protected AnimalRepository $repository,
        protected MultiDispatcher $dispatcher
    ) {
    }

    public function apply(
        UuidInterface $id,
        AnimalStatus $status
    ): Animal {
        $animal = $this->repository->getById($id);

        $animal->changeStatus($status);

        $this->repository->update(
            id: $id,
            animal: $animal
        );

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return $animal;
    }
}
