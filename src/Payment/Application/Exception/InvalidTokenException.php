<?php

declare(strict_types=1);

namespace App\Payment\Application\Exception;

use Throwable;
use Exception;

final class InvalidTokenException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
