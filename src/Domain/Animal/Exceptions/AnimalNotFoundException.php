<?php

namespace Source\Domain\Animal\Exceptions;

use Illuminate\Http\JsonResponse;
use Source\Infrastructure\Laravel\Exceptions\DefaultException;

class AnimalNotFoundException extends DefaultException
{
    protected $code = JsonResponse::HTTP_NOT_FOUND;
    protected $message = 'Animal doesn\'t exists';
}
