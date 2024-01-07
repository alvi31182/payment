<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

abstract class DomainEventRoot
{
    protected array $domainEvents;

    protected function recordEvent(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    /**
     * @return array<array-key, mixed>
     */
    public function pullDomainEvents(): array
    {
        $events = $this->domainEvents;

        $this->domainEvents = [];

        return $events;
    }
}
