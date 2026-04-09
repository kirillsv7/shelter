<?php

namespace Source\Domain\MediaFile\Exceptions;

use Source\Infrastructure\Laravel\Exceptions\NotFoundException;

class MediableNotFoundException extends NotFoundException
{
    protected $message = "Mediable doesn't exists";

}
