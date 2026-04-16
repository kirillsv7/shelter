<?php

namespace Source\Application\Organization\UseCases;

use Source\Application\Organization\DTOs\OrganizationDetailsDTO;
use Source\Application\Organization\DTOs\OrganizationDTO;
use Source\Application\Organization\DTOs\OrganizationResponseDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Organization\Repositories\OrganizationRepository;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Repositories\SlugRepository;

final class OrganizationGetBySlugUseCase
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected SlugRepository $slugRepository,
    ) {
    }

    public function apply(
        StringValueObject $slug,
    ): OrganizationResponseDTO {
        $animal = $this->organizationRepository->getBySlug(
            slug: $slug,
        );

        $slug = $this->slugRepository->getBySluggableUuid($animal->id);

        return new OrganizationResponseDTO(
            organization: new OrganizationDetailsDTO(
                organizationDTO: new OrganizationDTO($animal),
                slugDTO: new SlugDTO($slug),
            ),
        );
    }
}
