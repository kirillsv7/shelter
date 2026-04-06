<?php

namespace Source\Application\Animal\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;

final class AnimalDestroyUseCase
{
    public function __construct(
        protected AnimalRepository $animalRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(UuidInterface $id): void
    {
        $animal = $this->animalRepository->getById($id);

        $this->animalRepository->delete($id);

        $animal->delete();

        $this->dispatcher->multiDispatch($animal->releaseEvents());
    }
}
