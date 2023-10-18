<?php

namespace Source\Domain\Shared\Model\PaginationValueObjects;

use Source\Domain\Shared\ValueObjects\IntegerValueObject;

final readonly class Limit extends IntegerValueObject
{
    public static function fromInteger(int $value): static
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Limit can\'t be zero or negative number');
        }

        return new Limit($value);
    }
}
