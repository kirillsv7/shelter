<?php

namespace Source\Domain\MediaFile\Exceptions;

use Illuminate\Http\JsonResponse;
use Source\Infrastructure\Laravel\Exceptions\DefaultException;

class MediaFileNotFoundException extends DefaultException
{
    protected $code = JsonResponse::HTTP_NOT_FOUND;
    protected $message = 'MediaFile doesn\'t exists';
}
