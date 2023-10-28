<?php

namespace Source\Infrastructure\Laravel\Events;

use Illuminate\Contracts\Events\Dispatcher;

class LaravelMultiDispatcher implements MultiDispatcher
{
    public function __construct(
        public Dispatcher $dispatcher
    ) {
    }

    public function multiDispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}
