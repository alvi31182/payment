<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\UseCaseException;

use Exception;
use Throwable;

final class AppendPaymentException extends Exception
{
    public function __construct(string $message = "", ?Throwable $previous = null)
    {
        $code = 404;
        parent::__construct($message, $code, $previous);
    }
}
