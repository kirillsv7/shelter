<?php

namespace Source\Domain\Shared\ValueObjects;

final readonly class PathValueObject extends StringValueObject
{
    public static function fromArray(array $pathParts, string $directorySeparator = DIRECTORY_SEPARATOR): static
    {
        $string = implode($directorySeparator, $pathParts);

        return new self($string);
    }
}
