<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Constraints\EventsHas;
use Tests\Constraints\EventsHasNot;

abstract class UnitTestCase extends TestCase
{
    protected function assertEventsHas(string $eventClass, array $events)
    {
        static::assertThat($events, new EventsHas($eventClass));
    }

    protected function assertEventsHasNot(string $eventClass, array $events)
    {
        static::assertThat($events, new EventsHasNot($eventClass));
    }
}
