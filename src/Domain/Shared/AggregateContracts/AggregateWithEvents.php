<?php

namespace Source\Domain\Shared\AggregateContracts;

interface AggregateWithEvents
{
    public function releaseEvents(): array;
}
