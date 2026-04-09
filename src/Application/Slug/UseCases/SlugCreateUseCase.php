<?php

namespace Source\Application\Slug\UseCases;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Application\Slug\DTOs\SlugResponseDTO;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Domain\Slug\ValueObjects\SlugString;

final class SlugCreateUseCase
{
    public function __construct(
        protected SlugRepository $repository
    ) {
    }

    public function apply(
        string $slugString,
        StringValueObject $sluggableType,
        UuidInterface $sluggableId,
    ): SlugResponseDTO {
        $slug = Slug::create(
            id: Uuid::uuid7(),
            value: SlugString::fromString($slugString),
            sluggableType: $sluggableType,
            sluggableId: $sluggableId,
        );

        $this->repository->create($slug);

        return new SlugResponseDTO(
            slugDTO: new SlugDTO(
                slug: $slug,
            )
        );
    }
}
