<?php

declare(strict_types=1);

namespace App\Notify\Infrastructure\Transport\Exception;

use Symfony\Component\Messenger\Exception\TransportException;
use Throwable;

final class KafkaTransportException extends TransportException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
