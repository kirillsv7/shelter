<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Repositories\AnimalRepository;

final class AnimalDestroyUseCase
{
    public function __construct(
        protected AnimalRepository $repository
    ) {
    }

    public function apply(
        UuidInterface $id,
    ): void {
        $animal = $this->repository->getById($id);

        $this->repository->delete($id);

        $animal->delete();
    }
}
