<?php

namespace Source\Domain\Animal\Aggregates;

use Carbon\CarbonInterface;
use Ramsey\Uuid\UuidInterface;
use Source\Domain\Animal\Enums\AnimalStatus;
use Source\Domain\Animal\Events\AnimalCreated;
use Source\Domain\Animal\Events\AnimalDeleted;
use Source\Domain\Animal\Events\AnimalPublished;
use Source\Domain\Animal\Events\AnimalStatusUpdated;
use Source\Domain\Animal\Events\AnimalUnpublished;
use Source\Domain\Animal\ValueObjects\AnimalInfo;
use Source\Domain\Shared\AggregateTraits\UseAggregateEvents;
use Source\Domain\Shared\AggregateWithEvents;
use Source\Domain\Shared\ValueObjects\IntegerValueObject;

final class Animal implements AggregateWithEvents
{
    use UseAggregateEvents;

    protected function __construct(
        public readonly UuidInterface $id,
        public readonly AnimalInfo $info,
        protected AnimalStatus $status,
        protected int|bool $published = false,
        public readonly ?CarbonInterface $createdAt = null,
        public readonly ?CarbonInterface $updatedAt = null,
    ) {
    }

    public static function make(
        UuidInterface $id,
        AnimalInfo $info,
        AnimalStatus $status = AnimalStatus::Quarantine,
        int|bool $published = false,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
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
        AnimalStatus $status = AnimalStatus::Quarantine,
        int|bool $published = false,
        ?CarbonInterface $createdAt = null,
        ?CarbonInterface $updatedAt = null,
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
                $animal->id,
                $animal->info->name,
            ),
        );

        return $animal;
    }

    /**
     * Accessors
     */

    public function status(): AnimalStatus
    {
        return $this->status;
    }

    public function published(): bool
    {
        return $this->published;
    }

    /**
     * Computed
     */

    public function age(): IntegerValueObject
    {
        return IntegerValueObject::fromInteger($this->info->birthdate->age);
    }

    /**
     * Mutators
     */

    public function statusUpdate(AnimalStatus $status): bool
    {
        if ($this->status === $status) {
            return false;
        }

        $oldStatus    = $this->status;
        $this->status = $status;

        $this->addEvent(
            new AnimalStatusUpdated(
                $this->id,
                $this->info->name,
                $this->status,
                $oldStatus,
            ),
        );

        return true;
    }

    public function publish(): void
    {
        if ($this->published) {
            return;
        }

        $this->published = true;
        $this->addEvent(new AnimalPublished($this->id));
    }

    public function unpublish(): void
    {
        if (!$this->published) {
            return;
        }

        $this->published = false;
        $this->addEvent(new AnimalUnpublished($this->id));
    }

    public function delete(): void
    {
        $this->addEvent(new AnimalDeleted($this->id));
    }
}
