<?php

namespace Source\Domain\Slug\Exceptions;

use Illuminate\Http\JsonResponse;
use Source\Infrastructure\Laravel\Exceptions\DefaultException;

class SlugNotFoundException extends DefaultException
{
    protected $code = JsonResponse::HTTP_NOT_FOUND;
    protected $message = 'Slug doesn\'t exists';
}
