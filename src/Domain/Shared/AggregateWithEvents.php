<?php

namespace Source\Domain\Shared;

interface AggregateWithEvents
{
    public function releaseEvents(): array;
}
