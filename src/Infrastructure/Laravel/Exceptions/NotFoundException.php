<?php

namespace Source\Infrastructure\Laravel\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends DefaultException
{
    protected $code = Response::HTTP_NOT_FOUND;

    protected $message = "Model doesn't exists";
}
