<?php

namespace Source\Application\Slug\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;

final class SlugGetBySluggableUseCase
{
    public function __construct(
        protected SlugRepository $repository,
    ) {
    }

    public function apply(
        StringValueObject $sluggableType,
        UuidInterface $sluggableId,
    ): Slug {
        return $this->repository->getBySluggable(
            sluggableType: $sluggableType,
            sluggableId: $sluggableId,
        );
    }
}
