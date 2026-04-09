<?php

namespace Source\Application\Slug\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Application\Slug\DTOs\SlugResponseDTO;
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
    ): SlugResponseDTO {
        $slug = $this->repository->getBySluggableUuid($id);

        $slug->slugUpdate(
            SlugString::fromString($slugString),
        );

        $this->repository->update($slug);

        return new SlugResponseDTO(
            slug: new SlugDTO(
                slug: $slug
            )
        );
    }
}
