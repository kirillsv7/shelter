<?php

namespace Source\Domain\Animal\Enums;

use InvalidArgumentException;

enum AnimalType: string
{
    case Cat = 'cat';
    case Dog = 'dog';
    case Rodent = 'rodent';
    case Bird = 'bird';
    case Other = 'other';

    public static function single(string $multi): self
    {
        return match ($multi) {
            'cats' => self::Cat,
            'dogs' => self::Dog,
            'rodents' => self::Rodent,
            'birds' => self::Bird,
            'others' => self::Other,
            default => throw new InvalidArgumentException(
                sprintf("Single for %s doesn't exist", $multi)
            )
        };
    }
}
