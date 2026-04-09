<?php

namespace Source\Domain\Shared\AggregateTraits;

trait UseAggregateEvents
{
    private array $events = [];

    protected function addEvent(object $event): void
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
