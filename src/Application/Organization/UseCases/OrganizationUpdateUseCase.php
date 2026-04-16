<?php

namespace Source\Application\Organization\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Source\Application\Organization\DTOs\OrganizationDetailsDTO;
use Source\Application\Organization\DTOs\OrganizationDTO;
use Source\Application\Organization\DTOs\OrganizationResponseDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Organization\Aggregates\Organization;
use Source\Domain\Organization\Repositories\OrganizationRepository;
use Source\Domain\Organization\ValueObjects\Address;
use Source\Domain\Organization\ValueObjects\Contacts;
use Source\Domain\Organization\ValueObjects\Socials;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Interface\Organization\DTOs\OrganizationUpdateRequestDTO;

final class OrganizationUpdateUseCase
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected SlugRepository $slugRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(
        UuidInterface $id,
        OrganizationUpdateRequestDTO $dto,
    ): OrganizationResponseDTO {
        $organization = $this->organizationRepository->getById($id);

        $organization = Organization::make(
            id: Uuid::uuid7(),
            name: $dto->name,
            address: new Address(
                addressLine1: $dto->addressLine1,
                addressLine2: $dto->addressLine2,
                city: $dto->city,
                state: $dto->state,
                postcode: $dto->postcode,
                country: $dto->country,
            ),
            contacts: new Contacts(
                phone: $dto->phone,
                email: $dto->email,
                website: $dto->website,
            ),
            socials: new Socials(
                facebook: $dto->facebook,
                instagram: $dto->instagram,
                twitter: $dto->twitter,
                youtube: $dto->youtube,
                tiktok: $dto->tiktok,
            ),
            isVerified: $dto->isVerified,
            isActive: $dto->isActive,
            createdAt: $organization->createdAt,
            updatedAt: CarbonImmutable::now(),
        );

        $this->organizationRepository->update(
            id: $id,
            organization: $organization,
        );

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
