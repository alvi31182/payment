<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use React\Promise\PromiseInterface;

interface AsyncEventDispatcherInterface
{
    public function dispatch(DomainEvent $domainEvent): void;
}