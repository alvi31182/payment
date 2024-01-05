<?php

declare(strict_types=1);

namespace App\Payment\Application\Exception;

use Throwable;
use Exception;

class UnauthorizedException extends Exception
{
    public function __construct(string $message = "", int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
