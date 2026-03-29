<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\UseCases\Traits\LoadSlugTrait;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;

final class AnimalPublishUseCase
{
    use LoadSlugTrait;

    public function __construct(
        protected AnimalRepository $repository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(
        UuidInterface $id,
        StringValueObject $dateTimeFormat,
    ): Animal {
        $animal = $this->repository->getById(
            id: $id,
            dateTimeFormat: $dateTimeFormat,
        );

        $animal->publish();

        $this->repository->update($id, $animal);

        $this->loadSlug($animal);

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return $animal;
    }
}
