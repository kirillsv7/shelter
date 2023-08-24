<?php

namespace Source\Infrastructure\Laravel\Exceptions;

class WriteOperationIsNotAllowedForReadModel extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Writing operations not allowed in Read Model');
    }
}
