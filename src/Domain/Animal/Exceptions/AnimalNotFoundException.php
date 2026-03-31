<?php

namespace Source\Domain\Animal\Exceptions;

use Source\Infrastructure\Laravel\Exceptions\NotFoundException;

class AnimalNotFoundException extends NotFoundException
{
    protected $message = "Animal doesn't exists";
}
