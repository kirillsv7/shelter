<?php

namespace Source\Interface\Organization\DTOs;

use Source\Domain\Shared\ValueObjects\EmailValueObject;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class OrganizationUpdateRequestDTO
{
    public function __construct(
        public StringValueObject $name,
        public StringValueObject $addressLine1,
        public ?StringValueObject $addressLine2,
        public StringValueObject $city,
        public StringValueObject $state,
        public StringValueObject $postcode,
        public StringValueObject $country,
        public StringValueObject $phone,
        public EmailValueObject $email,
        public ?StringValueObject $website,
        public ?StringValueObject $facebook,
        public ?StringValueObject $instagram,
        public ?StringValueObject $twitter,
        public ?StringValueObject $youtube,
        public ?StringValueObject $tiktok,
        public bool $isVerified,
        public bool $isActive,
    ) {
    }
}
