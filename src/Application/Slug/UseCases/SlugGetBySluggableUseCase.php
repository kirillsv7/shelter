<?php

namespace Source\Application\Slug\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Application\Slug\DTOs\SlugResponseDTO;
use Source\Domain\Shared\ValueObjects\StringValueObject;
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
    ): SlugResponseDTO {
        $slug = $this->repository->getBySluggable(
            sluggableType: $sluggableType,
            sluggableId: $sluggableId,
        );

        return new SlugResponseDTO(
            slugDTO: new SlugDTO(
                slug: $slug,
            )
        );
    }
}
