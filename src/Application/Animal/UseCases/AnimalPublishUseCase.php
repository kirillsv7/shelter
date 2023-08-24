<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Repositories\AnimalRepository;

final class AnimalPublishUseCase
{
    public function __construct(
        protected AnimalRepository $repository
    ) {
    }

    public function apply(
        UuidInterface $id,
    ): Animal {
        $animal = $this->repository->getById($id);

        $animal->publish();

        $this->repository->update($id, $animal);

        return $animal;
    }
}
