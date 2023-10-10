<?php

namespace Source\Domain\Slug\Aggregates;

use Ramsey\Uuid\UuidInterface;
use Source\Domain\Shared\ValueObjects\StringValueObject;
use Source\Infrastructure\Laravel\Models\BaseModel;

final class Slug
{
    private array $events = [];

    private function __construct(
        private readonly ?int $id,
        private StringValueObject $value,
        private readonly BaseModel $sluggableType,
        private readonly UuidInterface $sluggableId,
    ) {
    }

    public static function create(
        ?int $id,
        StringValueObject $value,
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

    public function id(): int
    {
        return $this->id;
    }

    public function value(): StringValueObject
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

    public function changeSlug(StringValueObject $value)
    {
        $this->value = $value;
    }

    protected function addEvent($event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
