<?php

namespace Source\Domain\Slug\Aggregates;

use Carbon\CarbonInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\AggregateContracts\AggregateWithEvents;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Domain\Slug\Events\SlugCreated;
use Source\Domain\Slug\Events\SlugUpdated;
use Source\Domain\Slug\ValueObjects\SlugString;

final class Slug implements AggregateWithEvents
{
    use UseAggregateEvents;

    protected function __construct(
        public readonly UuidInterface $id,
        protected SlugString $value,
        public readonly StringValueObject $sluggableType,
        public readonly UuidInterface $sluggableId,
        public readonly ?CarbonInterface $createdAt = null,
        public readonly ?CarbonInterface $updatedAt = null,
    ) {
    }

    public static function make(
        UuidInterface $id,
        SlugString $value,
        StringValueObject $sluggableType,
        UuidInterface $sluggableId,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
    ): self {
        return new self(
            id: $id,
            value: $value,
            sluggableType: $sluggableType,
            sluggableId: $sluggableId,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public static function create(
        UuidInterface $id,
        SlugString $value,
        StringValueObject $sluggableType,
        UuidInterface $sluggableId,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
    ): self {
        $slug = self::make(
            id: $id,
            value: $value,
            sluggableType: $sluggableType,
            sluggableId: $sluggableId,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );

        $slug->addEvent(
            new SlugCreated($slug),
        );

        return $slug;
    }

    public function value(): SlugString
    {
        return $this->value;
    }

    public function slugUpdate(SlugString $value): void
    {
        if ($this->value->equals($value)) {
            return;
        }

        $oldSlug = $this->value;

        $this->value = $value;

        $this->addEvent(
            new SlugUpdated($this, $oldSlug),
        );
    }
}
