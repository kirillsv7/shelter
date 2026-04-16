<?php

namespace Source\Domain\Organization\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Source\Domain\Shared\ValueObjects\EmailValueObject;
use Source\Domain\Shared\ValueObjects\StringValueObject;

final readonly class Contacts implements Arrayable
{
    public function __construct(
        public StringValueObject $phone,
        public EmailValueObject $email,
        public ?StringValueObject $website,
    ) {
    }

    public function toArray(): array
    {
        return [
            'phone' => $this->phone->value(),
            'email' => $this->email->value(),
            'website' => $this->website?->value(),
        ];
    }
}
