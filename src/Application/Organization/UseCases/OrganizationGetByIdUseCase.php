<?php

namespace Source\Application\Organization\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Organization\DTOs\OrganizationDetailsDTO;
use Source\Application\Organization\DTOs\OrganizationDTO;
use Source\Application\Organization\DTOs\OrganizationResponseDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Organization\Repositories\OrganizationRepository;
use Source\Domain\Slug\Repositories\SlugRepository;

final class OrganizationGetByIdUseCase
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected SlugRepository $slugRepository,
    ) {
    }

    public function apply(UuidInterface $id): OrganizationResponseDTO
    {
        $organization = $this->organizationRepository->getById($id);

        $slug = $this->slugRepository->getBySluggableUuid($id);

        return new OrganizationResponseDTO(
            organization: new OrganizationDetailsDTO(
                organizationDTO: new OrganizationDTO($organization),
                slugDTO: new SlugDTO($slug),
            ),
        );
    }
}
