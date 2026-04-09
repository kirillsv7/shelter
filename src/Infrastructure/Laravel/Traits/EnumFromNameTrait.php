<?php

namespace Source\Infrastructure\Laravel\Traits;

use ValueError;

/**
 * @method static cases()
 */
trait EnumFromNameTrait
{
    public static function fromName(string $name): self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new ValueError(
            sprintf('%s is not a valid backing value for enum %s', $name, self::class),
        );
    }
}
