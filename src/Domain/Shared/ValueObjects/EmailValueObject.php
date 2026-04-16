<?php

namespace Source\Domain\Shared\ValueObjects;

use InvalidArgumentException;

final readonly class EmailValueObject extends StringValueObject
{
    protected function __construct(string $value)
    {
        parent::__construct($value);

        if (! $this->validate()) {
            throw new InvalidArgumentException('Invalid email address');
        }
    }

    protected function validate(): bool
    {
        return filter_var($this->value(), FILTER_VALIDATE_EMAIL);
    }
}
