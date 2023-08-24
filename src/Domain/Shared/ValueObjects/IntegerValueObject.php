<?php

namespace Source\Domain\Shared\ValueObjects;

readonly class IntegerValueObject
{
    final private function __construct(
        public int $value
    ) {
    }

    public static function fromInteger(int $value): static
    {
        return new static($value);
    }

    public function equals(IntegerValueObject $other): bool
    {
        return $this->value === $other->value;
    }

    public function isGreaterThan(IntegerValueObject $other): bool
    {
        return $this->value > $other->value;
    }

    public function isLessThan(IntegerValueObject $other): bool
    {
        return $this->value < $other->value;
    }

    public function increment(): static
    {
        return new static($this->value + 1);
    }

    public function decrement(): static
    {
        return new static($this->value - 1);
    }

    public function add(IntegerValueObject $term): static
    {
        return new static($this->value + $term->value);
    }

    public function subtract(IntegerValueObject $sub): static
    {
        return new static($this->value - $sub->value);
    }

    public function multiply(IntegerValueObject $factor): static
    {
        return new static($this->value * $factor->value);
    }

    public function divide(IntegerValueObject $divider): static
    {
        return new static((int) $this->value / $divider->value);
    }

    public function divideCeil(IntegerValueObject $divider): static
    {
        return new static((int)ceil($this->value / $divider->value));
    }

    public function max(IntegerValueObject $compared): IntegerValueObject
    {
        return new static(max($this->value, $compared->value));
    }

    public function min(IntegerValueObject $compared): IntegerValueObject
    {
        return new static(min($this->value, $compared->value));
    }

    public function __toString()
    {
        return (string)$this->value;
    }
}
