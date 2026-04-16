<?php

namespace Source\Domain\Organization\Exceptions;

use Source\Infrastructure\Laravel\Exceptions\NotFoundException;

class OrganizationNotFoundException extends NotFoundException
{
    protected $message = "Organization doesn't exists";
}
