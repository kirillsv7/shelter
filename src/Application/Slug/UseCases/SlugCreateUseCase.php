<?php

namespace Source\Application\Slug\UseCases;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Domain\Slug\ValueObjects\SlugString;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class SlugCreateUseCase
{
    public function __construct(
        protected SlugRepository $repository
    ) {
    }

    public function apply(
        string $slugString,
        BaseModel $sluggableType,
        UuidInterface $sluggableId,
    ): Slug {
        $slug = Slug::create(
            id: Uuid::uuid4(),
            value: SlugString::fromString($slugString),
            sluggableType: $sluggableType,
            sluggableId: $sluggableId
        );

        $this->repository->create($slug);

        return $slug;
    }
}
