<?php

namespace Source\Domain\Slug\Aggregates;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\AggregateWithEvents;
use Source\Domain\Slug\ValueObjects\SlugString;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class Slug implements AggregateWithEvents
{
    use UseAggregateEvents;

    private function __construct(
        private readonly UuidInterface $id,
        private SlugString $value,
        private readonly BaseModel $sluggableType,
        private readonly UuidInterface $sluggableId,
    ) {
    }

    public static function create(
        UuidInterface $id,
        SlugString $value,
        BaseModel $sluggableType,
        UuidInterface $sluggableId,
    ): Slug {
        return new self(
            id: $id,
            value: $value,
            sluggableType: $sluggableType,
            sluggableId: $sluggableId,
        );
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function value(): SlugString
    {
        return $this->value;
    }

    public function sluggableType(): string
    {
        return get_class($this->sluggableType);
    }

    public function sluggableId(): UuidInterface
    {
        return $this->sluggableId;
    }

    public function changeSlug(SlugString $value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
