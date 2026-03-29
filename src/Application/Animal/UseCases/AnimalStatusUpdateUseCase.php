<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\UseCases\Traits\LoadSlugTrait;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;

final class AnimalStatusUpdateUseCase
{
    use LoadSlugTrait;

    public function __construct(
        protected AnimalRepository $repository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(
        UuidInterface $id,
        AnimalStatus $status,
        StringValueObject $dateTimeFormat,
    ): Animal {
        $animal = $this->repository->getById(
            id: $id,
            dateTimeFormat: $dateTimeFormat,
        );

        $animal->changeStatus($status);

        $this->repository->update(
            id: $id,
            animal: $animal,
        );

        $this->loadSlug($animal);

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return $animal;
    }
}
