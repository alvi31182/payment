<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredOn(): DateTimeImmutable;
}
