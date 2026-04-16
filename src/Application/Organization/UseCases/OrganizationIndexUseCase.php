<?php

namespace Source\Application\Organization\UseCases;

use Source\Application\Organization\DTOs\OrganizationDetailsDTO;
use Source\Application\Organization\DTOs\OrganizationDTO;
use Source\Application\Organization\DTOs\OrganizationListResponseDTO;
use Source\Application\Shared\DTOs\PaginationDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Organization\Aggregates\Organization;
use Source\Domain\Organization\Repositories\OrganizationRepository;
use Source\Domain\Organization\Search\OrganizationSearchCriteria;
use Source\Domain\Shared\Model\Pagination;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Interface\Organization\DTOs\OrganizationIndexRequestDTO;

final class OrganizationIndexUseCase
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected SlugRepository $slugRepository,
    ) {
    }

    public function apply(OrganizationIndexRequestDTO $dto): OrganizationListResponseDTO
    {
        // TODO: Decouple pagination from repository
        $criteria = new OrganizationSearchCriteria(
            $dto->text,
            $dto->isVerified,
            $dto->isActive,
        );

        $pagination = Pagination::create(
            $dto->limit,
            $dto->page,
        );

        $organizations = $this->organizationRepository->index(
            $criteria,
            $pagination,
        );

        $organizationIds = array_map(
            fn (Organization $organization) => $organization->id,
            $organizations,
        );

        $slugs = $this->slugRepository->getBySluggableUuids($organizationIds);

        $organizationsTotalCount = $this->organizationRepository->totalCountByCriteria($criteria);

        $pagination->generateLinks($organizationsTotalCount);

        $organizationResponseDTOs = [];

        foreach ($organizations as $organization) {
            $organizationResponseDTOs[] = new OrganizationDetailsDTO(
                organizationDTO: new OrganizationDTO($organization),
                slugDTO: new SlugDTO(
                    array_find(
                        $slugs,
                        fn (Slug $slug) => $slug->sluggableId->equals($organization->id),
                    )
                ),
            );
        }

        return new OrganizationListResponseDTO(
            organizations: $organizationResponseDTOs,
            pagination: new PaginationDTO($pagination),
        );
    }
}
