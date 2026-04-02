<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\UseCases\Traits\LoadSlugTrait;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Animal\Models\AnimalModel;

final class AnimalGetByIdUseCase
{
    use LoadSlugTrait;

    public function __construct(
        protected AnimalRepository $repository,
    ) {
    }

    public function apply(UuidInterface $id): Animal
    {
        $animal = $this->repository->getById($id);

        $this->loadSlug(
            $animal,
            StringValueObject::fromString(AnimalModel::class),
        );

        return $animal;
    }
}
