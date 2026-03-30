<?php

namespace Source\Application\Animal\UseCases;

use Source\Domain\Animal\Aggregates\Animal;
use Source\Domain\Animal\Enums\AnimalType;
use Source\Domain\Animal\Repositories\AnimalRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final class AnimalGetBySlugUseCase
{
    public function __construct(
        protected AnimalRepository $repository
    ) {
    }

    public function apply(
        AnimalType $type,
        StringValueObject $slug,
    ): Animal {
        return $this->repository->getBySlug(
            type: $type,
            slug: $slug,
        );
    }
}
