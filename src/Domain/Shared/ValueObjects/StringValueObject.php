<?php

namespace Source\Domain\Shared\ValueObjects;

/** @phpstan-consistent-constructor */
readonly class StringValueObject
{
    protected function __construct(
        private string $value
    ) {
        if ($this->empty()) {
            throw new \InvalidArgumentException(sprintf('%s must have value', get_class($this)));
        }
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(StringValueObject $otherString): bool
    {
        return $this->value === $otherString->value;
    }

    public function empty(): bool
    {
        return empty($this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
