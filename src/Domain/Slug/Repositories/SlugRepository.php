<?php

namespace Source\Domain\Slug\Repositories;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Exceptions\SlugNotFoundException;
use Source\Infrastructure\Laravel\Models\BaseModel;

interface SlugRepository
{
    public function create(Slug $slug): int;

    /**
     * @throws SlugNotFoundException
     */
    public function getBySluggable(BaseModel $sluggableType, UuidInterface $sluggableId): ?Slug;
}
