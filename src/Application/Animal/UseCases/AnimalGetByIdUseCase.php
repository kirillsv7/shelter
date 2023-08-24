<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Repositories\AnimalRepository;

final class AnimalGetByIdUseCase
{
    public function __construct(
        protected AnimalRepository $repository,
    ) {
    }

    public function apply(
        UuidInterface $id
    ): Animal {
        return $this->repository->getById($id);
    }
}
