<?php

namespace Source\Domain\Slug\Exceptions;

use Source\Infrastructure\Laravel\Exceptions\NotFoundException;

class SlugNotFoundException extends NotFoundException
{
    protected $message = "Slug doesn't exists";
}
