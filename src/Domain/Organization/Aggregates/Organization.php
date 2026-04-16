<?php

namespace Source\Domain\Organization\Aggregates;

use Carbon\CarbonInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Organization\Events\OrganizationActivated;
use Source\Domain\Organization\Events\OrganizationCreated;
use Source\Domain\Organization\Events\OrganizationDeactivated;
use Source\Domain\Organization\Events\OrganizationDeleted;
use Source\Domain\Organization\Events\OrganizationUnverified;
use Source\Domain\Organization\Events\OrganizationVerified;
use Source\Domain\Organization\ValueObjects\Address;
use Source\Domain\Organization\ValueObjects\Contacts;
use Source\Domain\Organization\ValueObjects\Socials;
use Source\Domain\Shared\AggregateContracts\AggregateWithEvents;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final class Organization implements AggregateWithEvents
{
    use UseAggregateEvents;

    protected function __construct(
        public readonly UuidInterface $id,
        public readonly StringValueObject $name,
        public readonly Address $address,
        public readonly Contacts $contacts,
        public readonly Socials $socials,
        protected bool $isVerified = false,
        protected bool $isActive = false,
        public readonly ?CarbonInterface $createdAt = null,
        public readonly ?CarbonInterface $updatedAt = null,
    ) {
    }

    public static function make(
        UuidInterface $id,
        StringValueObject $name,
        Address $address,
        Contacts $contacts,
        Socials $socials,
        bool $isVerified = false,
        bool $isActive = false,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            address: $address,
            contacts: $contacts,
            socials: $socials,
            isVerified: $isVerified,
            isActive: $isActive,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public static function create(
        UuidInterface $id,
        StringValueObject $name,
        Address $address,
        Contacts $contacts,
        Socials $socials,
        bool $isVerified,
        bool $isActive,
        ?CarbonInterface $createdAt = null,
    ): self {
        $organization = self::make(
            id: $id,
            name: $name,
            address: $address,
            contacts: $contacts,
            socials: $socials,
            isVerified: $isVerified,
            isActive: $isActive,
            createdAt: $createdAt,
        );

        $organization->addEvent(
            new OrganizationCreated($organization),
        );

        return $organization;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function verify(): void
    {
        $this->isVerified = true;

        $this->addEvent(
            new OrganizationVerified($this),
        );
    }

    public function unverify(): void
    {
        $this->isVerified = false;

        $this->addEvent(
            new OrganizationUnverified($this),
        );
    }

    public function activate(): void
    {
        $this->isActive = true;

        $this->addEvent(
            new OrganizationActivated($this),
        );
    }

    public function deactivate(): void
    {
        $this->isActive = false;

        $this->addEvent(
            new OrganizationDeactivated($this),
        );
    }

    public function delete(): void
    {
        $this->addEvent(
            new OrganizationDeleted($this)
        );
    }
}
