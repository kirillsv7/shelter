<?php

namespace Source\Application\Slug\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Infrastructure\Laravel\Models\BaseModel;

class SlugGetBySluggableUseCase
{
    public function __construct(
        protected SlugRepository $repository
    ) {
    }

    public function apply(
        BaseModel $sluggableType,
        UuidInterface $sluggableId
    ): Slug {
        return $this->repository->getBySluggable(
            sluggableType: $sluggableType,
            sluggableId: $sluggableId
        );
    }
}
