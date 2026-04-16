<?php

namespace Source\Application\Organization\UseCases;

use Carbon\CarbonImmutable;
use Ramsey\Uuid\Uuid;
use Source\Application\Organization\DTOs\OrganizationDetailsDTO;
use Source\Application\Organization\DTOs\OrganizationDTO;
use Source\Application\Organization\DTOs\OrganizationResponseDTO;
use Source\Application\Slug\DTOs\SlugDTO;
use Source\Domain\Organization\Aggregates\Organization;
use Source\Domain\Organization\Repositories\OrganizationRepository;
use Source\Domain\Organization\ValueObjects\Address;
use Source\Domain\Organization\ValueObjects\Contacts;
use Source\Domain\Organization\ValueObjects\Socials;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Aggregates\Slug;
use Source\Domain\Slug\Repositories\SlugRepository;
use Source\Domain\Slug\ValueObjects\SlugString;
use Source\Infrastructure\Laravel\Events\MultiDispatcher;
use Source\Infrastructure\Organization\Models\OrganizationModel;
use Source\Interface\Organization\DTOs\OrganizationStoreRequestDTO;

final class OrganizationCreateUseCase
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
        protected SlugRepository $slugRepository,
        protected MultiDispatcher $dispatcher,
    ) {
    }

    public function apply(OrganizationStoreRequestDTO $dto): OrganizationResponseDTO
    {
        $organization = Organization::create(
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
            createdAt: CarbonImmutable::now(),
        );

        $slugParts = [
            $dto->name,
        ];

        $slug = Slug::create(
            id: Uuid::uuid7(),
            value: SlugString::fromArray($slugParts),
            sluggableType: StringValueObject::fromString(OrganizationModel::class),
            sluggableId: $organization->id,
        );

        $this->organizationRepository->create($organization);

        $this->slugRepository->create($slug);

        $this->dispatcher->multiDispatch($organization->releaseEvents());

        return new OrganizationResponseDTO(
            organization: new OrganizationDetailsDTO(
                organizationDTO: new OrganizationDTO($organization),
                slugDTO: new SlugDTO($slug),
            )
        );
    }
}
