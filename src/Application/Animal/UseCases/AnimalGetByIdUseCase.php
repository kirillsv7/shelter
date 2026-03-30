<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Animal\UseCases\Traits\LoadSlugTrait;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Repositories\AnimalRepository;

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

        $this->loadSlug($animal);

        return $animal;
    }
}
