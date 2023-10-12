<?php

namespace Source\Application\Slug\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Domain\Slug\ValueObjects\SlugString;

final class SlugUpdateUseCase
{
    public function __construct(
        protected SlugRepository $repository
    ) {
    }

    public function apply(
        UuidInterface $id,
        string $slugString,
    ): Slug {
        $slug = $this->repository->getBySluggableUuid($id);

        $slug->changeSlug(
            SlugString::fromString($slugString)
        );

        $this->repository->update($slug);

        return $slug;
    }
}
