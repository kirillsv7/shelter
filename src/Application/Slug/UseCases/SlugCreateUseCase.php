<?php

namespace Source\Application\Slug\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
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
            id: null,
            value: StringValueObject::fromString($slugString),
            sluggableType: $sluggableType,
            sluggableId: $sluggableId
        );

        $this->repository->create($slug);

        return $slug;
    }
}
