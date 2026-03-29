<?php

namespace Source\Infrastructure\Laravel\Traits;

use Source\Domain\Shared\ValueObjects\StringValueObject;

trait DateTimeFormatTrait
{
    public function dateTimeFormat(): StringValueObject
    {
        return StringValueObject::fromString(config('app.date_format'));
    }
}
