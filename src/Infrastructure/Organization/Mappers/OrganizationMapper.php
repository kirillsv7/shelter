<?php

namespace Source\Infrastructure\Organization\Mappers;

use Ramsey\Uuid\Uuid;
use Source\Domain\Organization\Aggregates\Organization;
use Source\Domain\Organization\ValueObjects\Address;
use Source\Domain\Organization\ValueObjects\Contacts;
use Source\Domain\Organization\ValueObjects\Socials;
use Source\Domain\Shared\ValueObjects\EmailValueObject;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Organization\Models\OrganizationModel;

final readonly class OrganizationMapper
{
    public function modelToEntity(OrganizationModel $model): Organization
    {
        $address = $model->getAttribute('address');

        $contacts = $model->getAttribute('contacts');

        $socials = $model->getAttribute('socials');

        return Organization::make(
            id: Uuid::fromString($model->getAttribute('id')),
            name: StringValueObject::fromString($model->getAttribute('name')),
            address: new Address(
                addressLine1: StringValueObject::fromString($address['addressLine1']),
                addressLine2: StringValueObject::fromString($address['addressLine2']),
                city: StringValueObject::fromString($address['city']),
                state: StringValueObject::fromString($address['state']),
                postcode: StringValueObject::fromString($address['postcode']),
                country: StringValueObject::fromString($address['country']),
            ),
            contacts: new Contacts(
                phone: StringValueObject::fromString($contacts['phone']),
                email: EmailValueObject::fromString($contacts['email']),
                website: $contacts['website']
                    ? StringValueObject::fromString($contacts['website'])
                    : null,
            ),
            socials: new Socials(
                facebook: array_key_exists('facebook', $socials)
                    ? StringValueObject::fromString($socials['facebook'])
                    : null,
                instagram: array_key_exists('instagram', $socials)
                    ? StringValueObject::fromString($socials['instagram'])
                    : null,
                twitter: array_key_exists('twitter', $socials)
                    ? StringValueObject::fromString($socials['twitter'])
                    : null,
                youtube: array_key_exists('youtube', $socials)
                    ? StringValueObject::fromString($socials['youtube'])
                    : null,
                tiktok: array_key_exists('tiktok', $socials)
                    ? StringValueObject::fromString($socials['tiktok'])
                    : null,
            ),
            isVerified: $model->getAttribute('is_verified'),
            isActive: $model->getAttribute('is_active'),
            createdAt: $model->getAttribute('created_at'),
            updatedAt: $model->getAttribute('updated_at'),
        );
    }

    public function entityToModel(
        Organization $organization,
        ?OrganizationModel $model = null,
    ): OrganizationModel {
        if (null === $model) {
            $model = new OrganizationModel();
        }

        $model->setAttribute('name', $organization->name);
        $model->setAttribute('address', $organization->address->toArray());
        $model->setAttribute('contacts', $organization->contacts->toArray());
        $model->setAttribute('socials', $organization->socials->toArray());
        $model->setAttribute('is_verified', $organization->isVerified());
        $model->setAttribute('is_active', $organization->isActive());

        return $model;
    }
}
