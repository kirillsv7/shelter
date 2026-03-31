<?php

namespace Source\Domain\MediaFile\Exceptions;

use Source\Infrastructure\Laravel\Exceptions\NotFoundException;

class MediaFileNotFoundException extends NotFoundException
{
    protected $message = "MediaFile doesn't exists";
}
