<?php

namespace Source\Application\Organization\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Organization\Repositories\OrganizationRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;

final class OrganizationDestroyUseCase
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(UuidInterface $id): void
    {
        $animal = $this->organizationRepository->getById($id);

        $this->organizationRepository->delete($id);

        $animal->delete();

        $this->dispatcher->multiDispatch($animal->releaseEvents());
    }
}
