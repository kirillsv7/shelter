<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final class AnimalDestroyUseCase
{
    public function __construct(
        protected AnimalRepository $repository
    ) {
    }

    public function apply(
        UuidInterface $id,
        StringValueObject $dateTimeFormat,
    ): void {
        $animal = $this->repository->getById(
            id: $id,
            dateTimeFormat: $dateTimeFormat,
        );

        $this->repository->delete($id);

        $animal->delete();
    }
}
