<?php

declare(strict_types=1);

namespace App\EventStorage\Application\Command;

final readonly class AppendEventCommand
{
    public function __construct(
        public string $eventName,
        public string $eventData
    )
    {
    }
}