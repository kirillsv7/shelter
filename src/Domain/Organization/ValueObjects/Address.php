<?php

namespace Source\Domain\Organization\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class Address implements Arrayable
{
    public function __construct(
        public StringValueObject $addressLine1,
        public ?StringValueObject $addressLine2,
        public StringValueObject $city,
        public StringValueObject $state,
        public StringValueObject $postcode,
        public StringValueObject $country,
    ) {
    }

    public function toArray(): array
    {
        return [
            'addressLine1' => $this->addressLine1->value(),
            'addressLine2' => $this->addressLine2?->value(),
            'city' => $this->city->value(),
            'state' => $this->state->value(),
            'postcode' => $this->postcode->value(),
            'country' => $this->country->value(),
        ];
    }
}
