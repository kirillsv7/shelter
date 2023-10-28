<?php

namespace Source\Domain\Animal\Aggregates;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Events\AnimalCreated;
use Source\Domain\Animal\Events\AnimalDeleted;
use Source\Domain\Animal\Events\AnimalPublished;
use Source\Domain\Animal\Events\AnimalStatusChanged;
use Source\Domain\Animal\Events\AnimalUnpublished;
use Source\Domain\Animal\ValueObjects\Slug;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\AggregateWithEvents;
use Source\Domain\Shared\Entity;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;

final class Animal implements Entity, AggregateWithEvents
{
    use UseAggregateEvents;

    private ?Slug $slug = null;

    private function __construct(
        private readonly UuidInterface $id,
        private readonly AnimalInfo $info,
        private AnimalStatus $status,
        private int|bool $published = false,
        private readonly ?Carbon $createdAt = null,
        private readonly ?Carbon $updatedAt = null
    ) {
    }

    public static function make(
        UuidInterface $id,
        AnimalInfo $info,
        AnimalStatus $status = AnimalStatus::Checking,
        int|bool $published = false,
        Carbon $createdAt = null,
        Carbon $updatedAt = null,
    ): self {
        return new self(
            id: $id,
            info: $info,
            status: $status,
            published: $published,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public static function create(
        UuidInterface $id,
        AnimalInfo $info,
        AnimalStatus $status = AnimalStatus::Checking,
        int|bool $published = false,
        Carbon $createdAt = null,
        Carbon $updatedAt = null,
    ): self {
        $animal = self::make(
            id: $id,
            info: $info,
            status: $status,
            published: $published,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );

        $animal->addEvent(
            new AnimalCreated(
                $animal->id(),
                $animal->info()->name()
            )
        );

        return $animal;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function info(): AnimalInfo
    {
        return $this->info;
    }

    public function age(): IntegerValueObject
    {
        return IntegerValueObject::fromInteger($this->info()->birthdate()->age);
    }

    public function status(): AnimalStatus
    {
        return $this->status;
    }

    public function published(): bool
    {
        return $this->published;
    }

    public function createdAt(): Carbon
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function slug(): ?Slug
    {
        return $this->slug;
    }

    public function changeStatus(AnimalStatus $status): void
    {
        if ($this->status === $status) {
            return;
        }

        $oldStatus = $this->status;
        $this->status = $status;

        $this->addEvent(
            new AnimalStatusChanged(
                $this->id(),
                $this->info()->name(),
                $this->status(),
                $oldStatus
            )
        );
    }

    public function publish(): void
    {
        if ($this->published) {
            return;
        }

        $this->published = true;
        $this->addEvent(new AnimalPublished($this->id()));
    }

    public function unpublish(): void
    {
        if (!$this->published) {
            return;
        }

        $this->published = false;
        $this->addEvent(new AnimalUnpublished($this->id()));
    }

    public function delete(): void
    {
        $this->addEvent(new AnimalDeleted($this->id()));
    }

    public function addSlug(Slug $slug): void
    {
        $this->slug = $slug;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'info' => $this->info()->toArray(),
            'age' => $this->age()->value,
            'status' => $this->status,
            'published' => $this->published(),
            'created_at' => $this->createdAt(),
            'updated_at' => $this->updatedAt(),
            'slug' => $this->slug()?->value(),
        ];
    }
}
