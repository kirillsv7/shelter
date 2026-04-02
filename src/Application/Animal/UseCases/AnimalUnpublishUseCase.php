<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\UseCases\Traits\LoadSlugTrait;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Models\AnimalModel;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;

final class AnimalUnpublishUseCase
{
    use LoadSlugTrait;

    public function __construct(
        protected AnimalRepository $repository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(UuidInterface $id): Animal
    {
        $animal = $this->repository->getById($id);

        $animal->unpublish();

        $this->repository->update($id, $animal);

        $this->loadSlug(
            $animal,
            StringValueObject::fromString(AnimalModel::class),
        );

        $this->dispatcher->multiDispatch($animal->releaseEvents());

        return $animal;
    }
}
