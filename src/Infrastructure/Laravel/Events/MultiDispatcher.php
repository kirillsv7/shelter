<?php

namespace Source\Infrastructure\Laravel\Events;

interface MultiDispatcher
{
    public function multiDispatch(array $events): void;
}
