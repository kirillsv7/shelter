<?php

namespace Source\Application\Organization\UseCases;

use Ramsey\Uuid\UuidInterface;
use Source\Application\Organization\DTOs\OrganizationDetailsDTO;
use Source\Application\Organization\DTOs\OrganizationDTO;
use Source\Application\Organization\DTOs\OrganizationResponseDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Organization\Repositories\OrganizationRepository;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;

final class OrganizationVerifyUseCase
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected SlugRepository $slugRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(UuidInterface $id): OrganizationResponseDTO
    {
        $organization = $this->organizationRepository->getById($id);

        $organization->verify();

        $this->organizationRepository->update($id, $organization);

        $this->dispatcher->multiDispatch($organization->releaseEvents());

        $slug = $this->slugRepository->getBySluggableUuid($id);

        return new OrganizationResponseDTO(
            organization: new OrganizationDetailsDTO(
                organizationDTO: new OrganizationDTO($organization),
                slugDTO: new SlugDTO($slug),
            )
        );
    }
}
