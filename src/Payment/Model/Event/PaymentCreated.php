<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use DateTimeImmutable;

/**
 * @psalm-immutable
 */
final readonly class PaymentCreated implements DomainEvent
{
    public function __construct(
        public string $id,
        public string $amount,
        public string $playerId
    ) {
    }

    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function getData(): array
    {
        return [
            'aggregateId' => $this->id,
            'amount' => $this->amount,
            'playerId' => $this->playerId
        ];
    }
}
